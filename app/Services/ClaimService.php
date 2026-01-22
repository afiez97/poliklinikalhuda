<?php

namespace App\Services;

use App\Models\ClaimAppeal;
use App\Models\ClaimDocument;
use App\Models\ClaimRejection;
use App\Models\GuaranteeLetter;
use App\Models\Invoice;
use App\Models\Panel;
use App\Models\PanelClaim;
use App\Models\PaymentAdvice;
use App\Models\PaymentReconciliation;
use App\Models\PreAuthorization;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClaimService
{
    // Pre-Authorization
    public function createPreAuthorization(array $data, int $userId): PreAuthorization
    {
        $data['pa_number'] = PreAuthorization::generatePANumber();
        $data['requested_by'] = $userId;
        $data['status'] = PreAuthorization::STATUS_DRAFT;

        // Handle supporting documents
        if (isset($data['documents']) && is_array($data['documents'])) {
            $paths = [];
            foreach ($data['documents'] as $file) {
                $paths[] = $file->store('pre-authorizations', 'public');
            }
            $data['supporting_documents'] = $paths;
            unset($data['documents']);
        }

        return PreAuthorization::create($data);
    }

    public function submitPreAuthorization(PreAuthorization $pa, int $userId): PreAuthorization
    {
        $pa->update([
            'status' => PreAuthorization::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);

        return $pa->fresh();
    }

    public function approvePreAuthorization(PreAuthorization $pa, float $amount, string $approvalNumber, ?string $remarks = null): PreAuthorization
    {
        $pa->update([
            'status' => PreAuthorization::STATUS_APPROVED,
            'approved_amount' => $amount,
            'approval_number' => $approvalNumber,
            'approval_expiry' => now()->addDays(30),
            'panel_remarks' => $remarks,
            'responded_at' => now(),
        ]);

        return $pa->fresh();
    }

    public function rejectPreAuthorization(PreAuthorization $pa, string $reason, ?string $remarks = null): PreAuthorization
    {
        $pa->update([
            'status' => PreAuthorization::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'panel_remarks' => $remarks,
            'responded_at' => now(),
        ]);

        return $pa->fresh();
    }

    // Claims
    public function getClaims(array $filters = []): LengthAwarePaginator
    {
        $query = PanelClaim::with(['panel', 'patient', 'guaranteeLetter', 'invoice'])
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('claim_number', 'like', "%{$search}%")
                        ->orWhereHas('patient', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['panel_id'] ?? null, fn ($q, $panelId) => $q->where('panel_id', $panelId))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('claim_status', $status))
            ->when($filters['date_from'] ?? null, fn ($q, $date) => $q->whereDate('claim_date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($q, $date) => $q->whereDate('claim_date', '<=', $date))
            ->when($filters['overdue'] ?? false, fn ($q) => $q->overdue())
            ->latest('claim_date');

        return $query->paginate(25)->withQueryString();
    }

    public function createClaim(array $data, int $userId): PanelClaim
    {
        return DB::transaction(function () use ($data, $userId) {
            $data['claim_number'] = PanelClaim::generateClaimNumber();
            $data['claim_date'] = now();
            $data['created_by'] = $userId;
            $data['claim_status'] = PanelClaim::STATUS_DRAFT;

            // Calculate amounts
            $invoice = Invoice::find($data['invoice_id']);
            $panel = Panel::with('packages')->find($data['panel_id']);
            $gl = isset($data['guarantee_letter_id']) ? GuaranteeLetter::find($data['guarantee_letter_id']) : null;

            $data['total_invoice_amount'] = $invoice->total_amount ?? $data['total_invoice_amount'] ?? 0;
            $data['service_date'] = $invoice->invoice_date ?? now();

            // Calculate co-payment, deductible, excluded
            $package = $panel->getDefaultPackage();
            if ($package) {
                $coveredAmount = $data['total_invoice_amount'] - ($data['excluded_amount'] ?? 0);
                $data['co_payment_amount'] = $package->calculateCoPayment($coveredAmount);
                $data['deductible_amount'] = $data['deductible_amount'] ?? $package->deductible_amount;
            }

            $data['claimable_amount'] = $data['total_invoice_amount']
                - ($data['co_payment_amount'] ?? 0)
                - ($data['deductible_amount'] ?? 0)
                - ($data['excluded_amount'] ?? 0);

            $claim = PanelClaim::create($data);

            return $claim;
        });
    }

    public function submitClaim(PanelClaim $claim, int $userId): PanelClaim
    {
        $panel = $claim->panel;
        $slaDays = $panel->sla_payment_days ?? 14;

        $claim->update([
            'claim_status' => PanelClaim::STATUS_SUBMITTED,
            'submitted_at' => now(),
            'submitted_by' => $userId,
            'sla_due_date' => now()->addDays($slaDays),
        ]);

        // Update GL utilization if applicable
        if ($claim->guarantee_letter_id) {
            $gl = $claim->guaranteeLetter;
            app(GLService::class)->recordUtilization(
                $gl,
                $claim->claimable_amount,
                $claim->invoice_id,
                $claim->encounter_id,
                'claim',
                "Claim #{$claim->claim_number}",
                $userId
            );
        }

        return $claim->fresh();
    }

    public function submitBatchClaims(array $claimIds, int $userId): array
    {
        $batchId = 'BATCH-'.now()->format('YmdHis');
        $submitted = [];
        $failed = [];

        foreach ($claimIds as $claimId) {
            try {
                $claim = PanelClaim::findOrFail($claimId);

                if ($claim->claim_status !== PanelClaim::STATUS_DRAFT) {
                    $failed[] = ['id' => $claimId, 'reason' => 'Status bukan draf'];

                    continue;
                }

                $claim->update(['batch_id' => $batchId]);
                $this->submitClaim($claim, $userId);
                $submitted[] = $claim;
            } catch (\Exception $e) {
                $failed[] = ['id' => $claimId, 'reason' => $e->getMessage()];
            }
        }

        return [
            'batch_id' => $batchId,
            'submitted' => $submitted,
            'failed' => $failed,
            'total_submitted' => count($submitted),
            'total_failed' => count($failed),
        ];
    }

    public function approveClaim(PanelClaim $claim, float $amount, ?string $remarks = null): PanelClaim
    {
        $claim->approve($amount, $remarks);

        return $claim->fresh();
    }

    public function rejectClaim(PanelClaim $claim, string $reason, ?string $remarks = null, ?int $userId = null): PanelClaim
    {
        return DB::transaction(function () use ($claim, $reason, $remarks, $userId) {
            $claim->reject($reason, $remarks);

            ClaimRejection::create([
                'panel_claim_id' => $claim->id,
                'rejection_date' => now(),
                'rejection_reason' => $reason,
                'rejected_amount' => $claim->claimable_amount,
                'panel_remarks' => $remarks,
                'is_appealable' => true,
                'recorded_by' => $userId,
            ]);

            return $claim->fresh();
        });
    }

    // Claim Documents
    public function attachDocument(PanelClaim $claim, $file, string $type, ?string $notes = null, ?int $userId = null): ClaimDocument
    {
        $path = $file->store('claim-documents', 'public');

        return ClaimDocument::create([
            'panel_claim_id' => $claim->id,
            'document_type' => $type,
            'document_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'notes' => $notes,
            'uploaded_by' => $userId,
        ]);
    }

    // Appeals
    public function createAppeal(PanelClaim $claim, array $data, int $userId): ClaimAppeal
    {
        $data['panel_claim_id'] = $claim->id;
        $data['appeal_number'] = ClaimAppeal::generateAppealNumber();
        $data['appeal_date'] = now();
        $data['original_amount'] = $claim->claimable_amount;
        $data['appealed_amount'] = $data['appealed_amount'] ?? $claim->claimable_amount;
        $data['status'] = ClaimAppeal::STATUS_SUBMITTED;
        $data['submitted_by'] = $userId;

        // Handle additional documents
        if (isset($data['documents']) && is_array($data['documents'])) {
            $paths = [];
            foreach ($data['documents'] as $file) {
                $paths[] = $file->store('claim-appeals', 'public');
            }
            $data['additional_documents'] = $paths;
            unset($data['documents']);
        }

        return ClaimAppeal::create($data);
    }

    // Payment Reconciliation
    public function createPaymentAdvice(array $data, int $userId): PaymentAdvice
    {
        $data['uploaded_by'] = $userId;
        $data['status'] = PaymentAdvice::STATUS_PENDING;

        // Handle file upload
        if (isset($data['file']) && $data['file']) {
            $data['file_path'] = $data['file']->store('payment-advices', 'public');
            unset($data['file']);
        }

        return PaymentAdvice::create($data);
    }

    public function reconcilePayment(PaymentAdvice $advice, array $payments, int $userId): array
    {
        return DB::transaction(function () use ($advice, $payments, $userId) {
            $matched = 0;
            $discrepancies = 0;
            $unmatched = 0;

            foreach ($payments as $payment) {
                $claim = PanelClaim::where('claim_number', $payment['claim_number'])->first();

                if (! $claim) {
                    $unmatched++;

                    continue;
                }

                $paidAmount = (float) $payment['paid_amount'];
                $approvedAmount = $claim->approved_amount ?? $claim->claimable_amount;
                $discrepancyAmount = $paidAmount - $approvedAmount;

                $matchStatus = PaymentReconciliation::STATUS_MATCHED;
                if ($discrepancyAmount < 0) {
                    $matchStatus = PaymentReconciliation::STATUS_SHORT_PAYMENT;
                    $discrepancies++;
                } elseif ($discrepancyAmount > 0) {
                    $matchStatus = PaymentReconciliation::STATUS_OVER_PAYMENT;
                    $discrepancies++;
                } else {
                    $matched++;
                }

                PaymentReconciliation::create([
                    'payment_advice_id' => $advice->id,
                    'panel_claim_id' => $claim->id,
                    'claimed_amount' => $claim->claimable_amount,
                    'approved_amount' => $approvedAmount,
                    'paid_amount' => $paidAmount,
                    'discrepancy_amount' => abs($discrepancyAmount),
                    'match_status' => $matchStatus,
                    'reconciled_by' => $userId,
                ]);

                // Mark claim as paid
                $claim->markAsPaid($paidAmount, $advice->payment_reference);
            }

            // Update advice
            $advice->update([
                'claim_count' => $matched + $discrepancies,
                'status' => PaymentAdvice::STATUS_COMPLETED,
                'processed_by' => $userId,
                'processed_at' => now(),
            ]);

            return [
                'total_processed' => count($payments),
                'matched' => $matched,
                'discrepancies' => $discrepancies,
                'unmatched' => $unmatched,
            ];
        });
    }

    // Reports & Statistics
    public function getClaimStatistics(array $filters = []): array
    {
        $query = PanelClaim::query()
            ->when($filters['panel_id'] ?? null, fn ($q, $panelId) => $q->where('panel_id', $panelId))
            ->when($filters['date_from'] ?? null, fn ($q, $date) => $q->whereDate('claim_date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($q, $date) => $q->whereDate('claim_date', '<=', $date));

        return [
            'total_claims' => (clone $query)->count(),
            'draft' => (clone $query)->draft()->count(),
            'submitted' => (clone $query)->submitted()->count(),
            'approved' => (clone $query)->approved()->count(),
            'rejected' => (clone $query)->rejected()->count(),
            'paid' => (clone $query)->paid()->count(),
            'overdue' => (clone $query)->overdue()->count(),
            'total_claimable' => (clone $query)->sum('claimable_amount'),
            'total_approved' => (clone $query)->sum('approved_amount'),
            'total_paid' => (clone $query)->sum('paid_amount'),
            'total_outstanding' => (clone $query)->outstanding()->sum('claimable_amount'),
        ];
    }

    public function getAgingReport(?int $panelId = null): array
    {
        $query = PanelClaim::outstanding();

        if ($panelId) {
            $query->where('panel_id', $panelId);
        }

        return [
            '0_30' => [
                'count' => (clone $query)->agingDays(0, 30)->count(),
                'amount' => (clone $query)->agingDays(0, 30)->sum('claimable_amount'),
            ],
            '31_60' => [
                'count' => (clone $query)->agingDays(31, 60)->count(),
                'amount' => (clone $query)->agingDays(31, 60)->sum('claimable_amount'),
            ],
            '61_90' => [
                'count' => (clone $query)->agingDays(61, 90)->count(),
                'amount' => (clone $query)->agingDays(61, 90)->sum('claimable_amount'),
            ],
            'over_90' => [
                'count' => (clone $query)->agingDays(91)->count(),
                'amount' => (clone $query)->agingDays(91)->sum('claimable_amount'),
            ],
        ];
    }

    public function getOutstandingClaims(?int $panelId = null): Collection
    {
        $query = PanelClaim::with(['panel', 'patient'])
            ->outstanding()
            ->orderBy('submitted_at');

        if ($panelId) {
            $query->where('panel_id', $panelId);
        }

        return $query->get();
    }

    public function checkSLAStatus(): int
    {
        return PanelClaim::outstanding()
            ->where('sla_due_date', '<', now())
            ->where('is_overdue', false)
            ->update(['is_overdue' => true]);
    }
}
