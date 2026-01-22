<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClaimDocument;
use App\Models\Panel;
use App\Models\PanelClaim;
use App\Models\PaymentAdvice;
use App\Models\PreAuthorization;
use App\Services\ClaimService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/panel/claims')]
#[Middleware(['web', 'auth'])]
class PanelClaimController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected ClaimService $claimService
    ) {}

    #[Get('/', name: 'admin.panel.claims.index')]
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'panel_id' => $request->input('panel_id'),
            'status' => $request->input('status'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'overdue' => $request->boolean('overdue'),
        ];

        $claims = $this->claimService->getClaims($filters);
        $panels = Panel::active()->orderBy('panel_name')->get();
        $statistics = $this->claimService->getClaimStatistics($filters);

        return view('admin.panel.claims.index', compact('claims', 'panels', 'filters', 'statistics'));
    }

    #[Get('/create', name: 'admin.panel.claims.create')]
    public function create(Request $request)
    {
        $panels = Panel::active()->with('packages')->orderBy('panel_name')->get();

        return view('admin.panel.claims.create', compact('panels'));
    }

    #[Post('/', name: 'admin.panel.claims.store')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'exists:invoices,id'],
            'panel_id' => ['required', 'exists:panels,id'],
            'guarantee_letter_id' => ['nullable', 'exists:guarantee_letters,id'],
            'patient_id' => ['required', 'exists:patients,id'],
            'encounter_id' => ['nullable', 'exists:encounters,id'],
            'pre_authorization_id' => ['nullable', 'exists:pre_authorizations,id'],
            'icd10_primary' => ['required', 'string', 'max:10'],
            'icd10_secondary' => ['nullable', 'array'],
            'total_invoice_amount' => ['required', 'numeric', 'min:0'],
            'co_payment_amount' => ['nullable', 'numeric', 'min:0'],
            'deductible_amount' => ['nullable', 'numeric', 'min:0'],
            'excluded_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $claim = $this->claimService->createClaim($validated, auth()->id());

            return $this->successRedirect(
                'admin.panel.claims.show',
                __('Tuntutan berjaya dicipta.'),
                ['claim' => $claim->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{claim}', name: 'admin.panel.claims.show')]
    public function show(PanelClaim $claim)
    {
        $claim->load([
            'panel',
            'patient',
            'guaranteeLetter',
            'invoice',
            'encounter',
            'preAuthorization',
            'documents',
            'rejections',
            'appeals',
        ]);

        return view('admin.panel.claims.show', compact('claim'));
    }

    #[Post('/{claim}/submit', name: 'admin.panel.claims.submit')]
    public function submit(PanelClaim $claim)
    {
        if ($claim->claim_status !== PanelClaim::STATUS_DRAFT) {
            return $this->errorRedirect('Tuntutan ini tidak dalam status draf.');
        }

        try {
            $this->claimService->submitClaim($claim, auth()->id());

            return $this->successRedirect(
                'admin.panel.claims.show',
                __('Tuntutan berjaya dihantar.'),
                ['claim' => $claim->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/batch-submit', name: 'admin.panel.claims.batchSubmit')]
    public function batchSubmit(Request $request)
    {
        $validated = $request->validate([
            'claim_ids' => ['required', 'array', 'min:1'],
            'claim_ids.*' => ['exists:panel_claims,id'],
        ]);

        try {
            $result = $this->claimService->submitBatchClaims($validated['claim_ids'], auth()->id());

            $message = "Batch {$result['batch_id']}: {$result['total_submitted']} tuntutan dihantar";
            if ($result['total_failed'] > 0) {
                $message .= ", {$result['total_failed']} gagal";
            }

            return $this->successRedirect('admin.panel.claims.index', $message);
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/{claim}/approve', name: 'admin.panel.claims.approve')]
    public function approve(Request $request, PanelClaim $claim)
    {
        $validated = $request->validate([
            'approved_amount' => ['required', 'numeric', 'min:0'],
            'panel_remarks' => ['nullable', 'string'],
        ]);

        try {
            $this->claimService->approveClaim(
                $claim,
                $validated['approved_amount'],
                $validated['panel_remarks'] ?? null
            );

            return $this->successRedirect(
                'admin.panel.claims.show',
                __('Tuntutan berjaya diluluskan.'),
                ['claim' => $claim->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/{claim}/reject', name: 'admin.panel.claims.reject')]
    public function reject(Request $request, PanelClaim $claim)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string'],
            'panel_remarks' => ['nullable', 'string'],
        ]);

        try {
            $this->claimService->rejectClaim(
                $claim,
                $validated['rejection_reason'],
                $validated['panel_remarks'] ?? null,
                auth()->id()
            );

            return $this->successRedirect(
                'admin.panel.claims.show',
                __('Tuntutan telah ditolak.'),
                ['claim' => $claim->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/{claim}/documents', name: 'admin.panel.claims.attachDocument')]
    public function attachDocument(Request $request, PanelClaim $claim)
    {
        $validated = $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'document_type' => ['required', 'in:gl_copy,invoice,medical_certificate,lab_report,prescription,pa_approval,referral_letter,other'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $this->claimService->attachDocument(
                $claim,
                $request->file('document'),
                $validated['document_type'],
                $validated['notes'] ?? null,
                auth()->id()
            );

            return $this->successRedirect(
                'admin.panel.claims.show',
                __('Dokumen berjaya dimuat naik.'),
                ['claim' => $claim->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Delete('/{claim}/documents/{document}', name: 'admin.panel.claims.deleteDocument')]
    public function deleteDocument(PanelClaim $claim, ClaimDocument $document)
    {
        $document->delete();

        return $this->successRedirect(
            'admin.panel.claims.show',
            __('Dokumen berjaya dipadam.'),
            ['claim' => $claim->id]
        );
    }

    #[Post('/{claim}/appeal', name: 'admin.panel.claims.appeal')]
    public function appeal(Request $request, PanelClaim $claim)
    {
        $validated = $request->validate([
            'appeal_reason' => ['required', 'string'],
            'appealed_amount' => ['nullable', 'numeric', 'min:0'],
            'supporting_notes' => ['nullable', 'string'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        try {
            $this->claimService->createAppeal($claim, $validated, auth()->id());

            return $this->successRedirect(
                'admin.panel.claims.show',
                __('Rayuan berjaya dihantar.'),
                ['claim' => $claim->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    // Pre-Authorization
    #[Get('/pa', name: 'admin.panel.pa.index')]
    public function paIndex(Request $request)
    {
        $preAuthorizations = PreAuthorization::with(['panel', 'patient'])
            ->when($request->input('search'), function ($q, $search) {
                $q->where('pa_number', 'like', "%{$search}%")
                    ->orWhereHas('patient', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->when($request->input('panel_id'), fn ($q, $panelId) => $q->where('panel_id', $panelId))
            ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(25);

        $panels = Panel::active()->orderBy('panel_name')->get();

        return view('admin.panel.pa.index', compact('preAuthorizations', 'panels'));
    }

    #[Get('/pa/create', name: 'admin.panel.pa.create')]
    public function paCreate()
    {
        $panels = Panel::active()->orderBy('panel_name')->get();

        return view('admin.panel.pa.create', compact('panels'));
    }

    #[Post('/pa', name: 'admin.panel.pa.store')]
    public function paStore(Request $request)
    {
        $validated = $request->validate([
            'panel_id' => ['required', 'exists:panels,id'],
            'patient_id' => ['required', 'exists:patients,id'],
            'guarantee_letter_id' => ['nullable', 'exists:guarantee_letters,id'],
            'procedure_code' => ['nullable', 'string', 'max:50'],
            'procedure_name' => ['required', 'string', 'max:255'],
            'estimated_cost' => ['required', 'numeric', 'min:0'],
            'icd10_primary' => ['nullable', 'string', 'max:10'],
            'clinical_justification' => ['nullable', 'string'],
            'requested_date' => ['required', 'date'],
            'procedure_date' => ['nullable', 'date'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        try {
            $pa = $this->claimService->createPreAuthorization($validated, auth()->id());

            return $this->successRedirect(
                'admin.panel.pa.show',
                __('Pre-Authorization berjaya dicipta.'),
                ['pa' => $pa->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/pa/{pa}', name: 'admin.panel.pa.show')]
    public function paShow(PreAuthorization $pa)
    {
        $pa->load(['panel', 'patient', 'guaranteeLetter', 'claims']);

        return view('admin.panel.pa.show', compact('pa'));
    }

    #[Post('/pa/{pa}/submit', name: 'admin.panel.pa.submit')]
    public function paSubmit(PreAuthorization $pa)
    {
        try {
            $this->claimService->submitPreAuthorization($pa, auth()->id());

            return $this->successRedirect(
                'admin.panel.pa.show',
                __('Pre-Authorization berjaya dihantar.'),
                ['pa' => $pa->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/pa/{pa}/approve', name: 'admin.panel.pa.approve')]
    public function paApprove(Request $request, PreAuthorization $pa)
    {
        $validated = $request->validate([
            'approved_amount' => ['required', 'numeric', 'min:0'],
            'approval_number' => ['required', 'string', 'max:100'],
            'panel_remarks' => ['nullable', 'string'],
        ]);

        try {
            $this->claimService->approvePreAuthorization(
                $pa,
                $validated['approved_amount'],
                $validated['approval_number'],
                $validated['panel_remarks'] ?? null
            );

            return $this->successRedirect(
                'admin.panel.pa.show',
                __('Pre-Authorization berjaya diluluskan.'),
                ['pa' => $pa->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    // Payment Reconciliation
    #[Get('/reconciliation', name: 'admin.panel.reconciliation.index')]
    public function reconciliationIndex()
    {
        $paymentAdvices = PaymentAdvice::with(['panel', 'uploadedBy'])
            ->latest()
            ->paginate(25);

        $panels = Panel::active()->orderBy('panel_name')->get();

        return view('admin.panel.reconciliation.index', compact('paymentAdvices', 'panels'));
    }

    #[Get('/reconciliation/create', name: 'admin.panel.reconciliation.create')]
    public function reconciliationCreate()
    {
        $panels = Panel::active()->orderBy('panel_name')->get();

        return view('admin.panel.reconciliation.create', compact('panels'));
    }

    #[Post('/reconciliation', name: 'admin.panel.reconciliation.store')]
    public function reconciliationStore(Request $request)
    {
        $validated = $request->validate([
            'panel_id' => ['required', 'exists:panels,id'],
            'advice_number' => ['nullable', 'string', 'max:100'],
            'advice_date' => ['required', 'date'],
            'payment_date' => ['nullable', 'date'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'payment_method' => ['nullable', 'in:cheque,bank_transfer,online,cash'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'file' => ['nullable', 'file', 'mimes:pdf,xlsx,csv', 'max:10240'],
            'remarks' => ['nullable', 'string'],
        ]);

        try {
            $advice = $this->claimService->createPaymentAdvice($validated, auth()->id());

            return $this->successRedirect(
                'admin.panel.reconciliation.show',
                __('Payment Advice berjaya dicipta.'),
                ['advice' => $advice->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/reconciliation/{advice}', name: 'admin.panel.reconciliation.show')]
    public function reconciliationShow(PaymentAdvice $advice)
    {
        $advice->load(['panel', 'reconciliations.claim']);

        // Get outstanding claims for this panel
        $outstandingClaims = $this->claimService->getOutstandingClaims($advice->panel_id);

        return view('admin.panel.reconciliation.show', compact('advice', 'outstandingClaims'));
    }

    #[Post('/reconciliation/{advice}/process', name: 'admin.panel.reconciliation.process')]
    public function reconciliationProcess(Request $request, PaymentAdvice $advice)
    {
        $validated = $request->validate([
            'payments' => ['required', 'array', 'min:1'],
            'payments.*.claim_number' => ['required', 'string'],
            'payments.*.paid_amount' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $result = $this->claimService->reconcilePayment($advice, $validated['payments'], auth()->id());

            $message = "Pemadanan selesai: {$result['matched']} sepadan, {$result['discrepancies']} percanggahan, {$result['unmatched']} tidak dijumpai.";

            return $this->successRedirect(
                'admin.panel.reconciliation.show',
                $message,
                ['advice' => $advice->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    // Reports
    #[Get('/reports', name: 'admin.panel.reports.index')]
    public function reports(Request $request)
    {
        $filters = [
            'panel_id' => $request->input('panel_id'),
            'date_from' => $request->input('date_from', now()->startOfMonth()->toDateString()),
            'date_to' => $request->input('date_to', now()->endOfMonth()->toDateString()),
        ];

        $panels = Panel::active()->orderBy('panel_name')->get();
        $statistics = $this->claimService->getClaimStatistics($filters);
        $agingReport = $this->claimService->getAgingReport($filters['panel_id']);

        return view('admin.panel.reports.index', compact('panels', 'filters', 'statistics', 'agingReport'));
    }
}
