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

    #[Post('/batch-submit', name: 'admin.panel.claims.batch-submit')]
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

    // Pre-Authorization (MUST be before /{claim} wildcard)
    #[Get('/pa', name: 'admin.panel.pa.index')]
    public function paIndex(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'panel_id' => $request->input('panel_id'),
            'status' => $request->input('status'),
            'type' => $request->input('type'),
        ];

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
        $statistics = [
            'pending' => PreAuthorization::where('status', 'pending')->count(),
            'approved' => PreAuthorization::where('status', 'approved')->count(),
            'rejected' => PreAuthorization::where('status', 'rejected')->count(),
            'expired' => PreAuthorization::where('status', 'expired')->count(),
        ];

        return view('admin.panel.pa.index', compact('preAuthorizations', 'panels', 'filters', 'statistics'));
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

    #[Post('/pa/{pa}/reject', name: 'admin.panel.pa.reject')]
    public function paReject(Request $request, PreAuthorization $pa)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        try {
            $pa->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
            ]);

            return $this->successRedirect(
                'admin.panel.pa.show',
                __('Pre-Authorization telah ditolak.'),
                ['pa' => $pa->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    // Payment Reconciliation (MUST be before /{claim} wildcard)
    #[Get('/reconciliation', name: 'admin.panel.reconciliation.index')]
    public function reconciliationIndex(Request $request)
    {
        $filters = [
            'panel_id' => $request->input('panel_id'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'status' => $request->input('status'),
        ];

        $panels = Panel::active()->orderBy('panel_name')->get();

        // Outstanding claims
        $outstandingClaims = PanelClaim::with('panel')
            ->whereIn('claim_status', [PanelClaim::STATUS_APPROVED])
            ->where(function ($q) {
                $q->whereNull('paid_amount')
                    ->orWhereRaw('paid_amount < approved_amount');
            })
            ->when($filters['panel_id'], fn ($q, $panelId) => $q->where('panel_id', $panelId))
            ->orderBy('submitted_at')
            ->paginate(25, ['*'], 'outstanding_page');

        // Recent payments
        $recentPayments = PaymentAdvice::with('panel')
            ->latest('payment_date')
            ->limit(10)
            ->get();

        // Statistics
        $statistics = [
            'outstanding' => PanelClaim::whereIn('claim_status', [PanelClaim::STATUS_APPROVED])
                ->selectRaw('COALESCE(SUM(approved_amount - COALESCE(paid_amount, 0)), 0) as total')
                ->value('total'),
            'received_this_month' => PaymentAdvice::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('total_amount'),
            'claims_pending' => PanelClaim::where('claim_status', PanelClaim::STATUS_APPROVED)
                ->where(function ($q) {
                    $q->whereNull('paid_amount')
                        ->orWhereRaw('paid_amount < approved_amount');
                })
                ->count(),
            'overdue_count' => PanelClaim::where('claim_status', PanelClaim::STATUS_APPROVED)
                ->where('submitted_at', '<', now()->subDays(60))
                ->where(function ($q) {
                    $q->whereNull('paid_amount')
                        ->orWhereRaw('paid_amount < approved_amount');
                })
                ->count(),
        ];

        // Aging analysis
        $aging = [
            '0_30' => PanelClaim::whereIn('claim_status', [PanelClaim::STATUS_APPROVED])
                ->where('submitted_at', '>=', now()->subDays(30))
                ->selectRaw('COALESCE(SUM(approved_amount - COALESCE(paid_amount, 0)), 0) as total')
                ->value('total'),
            '31_60' => PanelClaim::whereIn('claim_status', [PanelClaim::STATUS_APPROVED])
                ->whereBetween('submitted_at', [now()->subDays(60), now()->subDays(30)])
                ->selectRaw('COALESCE(SUM(approved_amount - COALESCE(paid_amount, 0)), 0) as total')
                ->value('total'),
            '61_90' => PanelClaim::whereIn('claim_status', [PanelClaim::STATUS_APPROVED])
                ->whereBetween('submitted_at', [now()->subDays(90), now()->subDays(60)])
                ->selectRaw('COALESCE(SUM(approved_amount - COALESCE(paid_amount, 0)), 0) as total')
                ->value('total'),
            '90_plus' => PanelClaim::whereIn('claim_status', [PanelClaim::STATUS_APPROVED])
                ->where('submitted_at', '<', now()->subDays(90))
                ->selectRaw('COALESCE(SUM(approved_amount - COALESCE(paid_amount, 0)), 0) as total')
                ->value('total'),
        ];

        $agingCount = [
            '0_30' => PanelClaim::whereIn('claim_status', [PanelClaim::STATUS_APPROVED])
                ->where('submitted_at', '>=', now()->subDays(30))
                ->count(),
            '31_60' => PanelClaim::whereIn('claim_status', [PanelClaim::STATUS_APPROVED])
                ->whereBetween('submitted_at', [now()->subDays(60), now()->subDays(30)])
                ->count(),
            '61_90' => PanelClaim::whereIn('claim_status', [PanelClaim::STATUS_APPROVED])
                ->whereBetween('submitted_at', [now()->subDays(90), now()->subDays(60)])
                ->count(),
            '90_plus' => PanelClaim::whereIn('claim_status', [PanelClaim::STATUS_APPROVED])
                ->where('submitted_at', '<', now()->subDays(90))
                ->count(),
        ];

        return view('admin.panel.reconciliation.index', compact(
            'panels',
            'filters',
            'outstandingClaims',
            'recentPayments',
            'statistics',
            'aging',
            'agingCount'
        ));
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

    // Reports (MUST be before /{claim} wildcard)
    #[Get('/reports', name: 'admin.panel.reports.index')]
    public function reports(Request $request)
    {
        $filters = [
            'report_type' => $request->input('report_type', 'summary'),
            'panel_id' => $request->input('panel_id'),
            'date_from' => $request->input('date_from', now()->startOfMonth()->toDateString()),
            'date_to' => $request->input('date_to', now()->endOfMonth()->toDateString()),
        ];

        $panels = Panel::active()->orderBy('panel_name')->get();

        // Statistics
        $statistics = [
            'total_panels' => Panel::active()->count(),
            'active_gls' => \App\Models\GuaranteeLetter::where('status', 'active')->count(),
            'total_claims_amount' => PanelClaim::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('claimable_amount'),
            'outstanding_amount' => PanelClaim::whereIn('claim_status', [PanelClaim::STATUS_APPROVED])
                ->selectRaw('COALESCE(SUM(approved_amount - COALESCE(paid_amount, 0)), 0) as total')
                ->value('total'),
        ];

        // Panel summary
        $panelSummary = Panel::withCount(['guaranteeLetters as active_gls' => function ($q) {
            $q->where('status', 'active');
        }])
            ->withCount(['claims as total_claims'])
            ->withSum('claims as claims_amount', 'claimable_amount')
            ->withSum(['claims as paid_amount' => function ($q) {
                $q->where('claim_status', PanelClaim::STATUS_PAID);
            }], 'paid_amount')
            ->get()
            ->map(function ($panel) {
                $panel->outstanding_amount = ($panel->claims_amount ?? 0) - ($panel->paid_amount ?? 0);

                return $panel;
            });

        // Monthly trend (last 6 months)
        $monthlyTrend = [
            'labels' => [],
            'claims' => [],
            'paid' => [],
        ];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyTrend['labels'][] = $month->format('M Y');
            $monthlyTrend['claims'][] = PanelClaim::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('claimable_amount');
            $monthlyTrend['paid'][] = PanelClaim::where('claim_status', PanelClaim::STATUS_PAID)
                ->whereMonth('paid_at', $month->month)
                ->whereYear('paid_at', $month->year)
                ->sum('paid_amount');
        }

        // Panel type distribution
        $panelTypeDistribution = [
            'corporate' => Panel::where('panel_type', 'corporate')->count(),
            'insurance' => Panel::where('panel_type', 'insurance')->count(),
            'government' => Panel::where('panel_type', 'government')->count(),
        ];

        // Claim status distribution
        $claimStatusDistribution = [
            'draft' => PanelClaim::where('claim_status', PanelClaim::STATUS_DRAFT)->count(),
            'submitted' => PanelClaim::where('claim_status', PanelClaim::STATUS_SUBMITTED)->count(),
            'approved' => PanelClaim::where('claim_status', PanelClaim::STATUS_APPROVED)->count(),
            'rejected' => PanelClaim::where('claim_status', PanelClaim::STATUS_REJECTED)->count(),
            'paid' => PanelClaim::where('claim_status', PanelClaim::STATUS_PAID)->count(),
        ];

        return view('admin.panel.reports.index', compact(
            'panels',
            'filters',
            'statistics',
            'panelSummary',
            'monthlyTrend',
            'panelTypeDistribution',
            'claimStatusDistribution'
        ));
    }

    // Claims CRUD routes with wildcard (MUST be AFTER specific routes)
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
            'panelEmployee',
        ]);

        return view('admin.panel.claims.show', compact('claim'));
    }

    #[Get('/{claim}/edit', name: 'admin.panel.claims.edit')]
    public function edit(PanelClaim $claim)
    {
        $claim->load(['panel', 'patient', 'guaranteeLetter', 'invoice', 'documents']);

        $availableGLs = \App\Models\GuaranteeLetter::where('panel_id', $claim->panel_id)
            ->where('patient_id', $claim->patient_id)
            ->where('status', 'active')
            ->get();

        return view('admin.panel.claims.edit', compact('claim', 'availableGLs'));
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

    #[Post('/{claim}/record-payment', name: 'admin.panel.claims.record-payment')]
    public function recordPayment(Request $request, PanelClaim $claim)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_date' => ['required', 'date'],
            'reference' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $claim->update([
                'paid_amount' => ($claim->paid_amount ?? 0) + $validated['amount'],
                'paid_at' => $validated['payment_date'],
                'payment_reference' => $validated['reference'],
                'claim_status' => ($claim->paid_amount ?? 0) + $validated['amount'] >= $claim->approved_amount
                    ? PanelClaim::STATUS_PAID
                    : PanelClaim::STATUS_APPROVED,
            ]);

            return $this->successRedirect(
                'admin.panel.claims.show',
                __('Bayaran berjaya direkod.'),
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
}
