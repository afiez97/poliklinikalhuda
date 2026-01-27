<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/reports')]
#[Middleware(['web', 'auth'])]
class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    #[Get('/', name: 'admin.reports.index')]
    public function index()
    {
        return redirect()->route('admin.reports.executive');
    }

    #[Get('/executive', name: 'admin.reports.executive')]
    public function executive(Request $request)
    {
        $filters = [
            'date_from' => $request->input('date_from', now()->startOfMonth()->toDateString()),
            'date_to' => $request->input('date_to', now()->toDateString()),
        ];

        $data = $this->reportService->getExecutiveDashboard($filters);

        return view('admin.reports.executive', compact('data', 'filters'));
    }

    #[Get('/operational', name: 'admin.reports.operational')]
    public function operational()
    {
        $data = $this->reportService->getOperationalDashboard();

        return view('admin.reports.operational', compact('data'));
    }

    #[Get('/clinical', name: 'admin.reports.clinical')]
    public function clinical(Request $request)
    {
        $filters = [
            'date_from' => $request->input('date_from', now()->startOfMonth()->toDateString()),
            'date_to' => $request->input('date_to', now()->toDateString()),
        ];

        $data = $this->reportService->getClinicalDashboard($filters);

        return view('admin.reports.clinical', compact('data', 'filters'));
    }

    #[Get('/pharmacy', name: 'admin.reports.pharmacy')]
    public function pharmacy()
    {
        $data = $this->reportService->getPharmacyDashboard();

        return view('admin.reports.pharmacy', compact('data'));
    }

    #[Get('/financial', name: 'admin.reports.financial')]
    public function financial(Request $request)
    {
        $filters = [
            'date_from' => $request->input('date_from', now()->startOfMonth()->toDateString()),
            'date_to' => $request->input('date_to', now()->toDateString()),
            'group_by' => $request->input('group_by', 'day'),
        ];

        $data = $this->getFinancialData($filters);

        return view('admin.reports.financial', compact('data', 'filters'));
    }

    #[Get('/patients', name: 'admin.reports.patients')]
    public function patients(Request $request)
    {
        $filters = [
            'date_from' => $request->input('date_from', now()->startOfMonth()->toDateString()),
            'date_to' => $request->input('date_to', now()->toDateString()),
        ];

        $data = $this->getPatientData($filters);

        return view('admin.reports.patients', compact('data', 'filters'));
    }

    protected function getFinancialData(array $filters): array
    {
        $dateFrom = $filters['date_from'];
        $dateTo = $filters['date_to'];

        // Revenue by payment method
        $byMethod = \App\Models\Payment::selectRaw('payment_method, SUM(amount) as total')
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->get();

        // Daily revenue
        $dailyRevenue = \App\Models\Payment::selectRaw('DATE(payment_date) as date, SUM(amount) as total')
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Outstanding invoices
        $outstanding = \App\Models\Invoice::where('status', 'unpaid')
            ->whereBetween('invoice_date', [$dateFrom, $dateTo])
            ->sum('total_amount');

        // Panel claims
        $panelClaims = \App\Models\PanelClaim::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('claim_status, COUNT(*) as count, SUM(claimable_amount) as total')
            ->groupBy('claim_status')
            ->get();

        return [
            'total_revenue' => $dailyRevenue->sum('total'),
            'outstanding' => $outstanding,
            'by_method' => $byMethod,
            'daily_revenue' => $dailyRevenue,
            'panel_claims' => $panelClaims,
        ];
    }

    protected function getPatientData(array $filters): array
    {
        $dateFrom = $filters['date_from'];
        $dateTo = $filters['date_to'];

        // New vs returning patients
        $newPatients = \App\Models\Patient::whereBetween('created_at', [$dateFrom, $dateTo])->count();

        // Visits
        $visits = \App\Models\Encounter::whereBetween('encounter_date', [$dateFrom, $dateTo])->count();

        // Age distribution
        $ageDistribution = \App\Models\Patient::selectRaw("
            CASE
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 18 THEN 'Kanak-kanak'
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 40 THEN 'Dewasa'
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 60 THEN 'Pertengahan'
                ELSE 'Warga Emas'
            END as age_group,
            COUNT(*) as count
        ")
            ->whereHas('encounters', fn($q) => $q->whereBetween('encounter_date', [$dateFrom, $dateTo]))
            ->groupBy('age_group')
            ->get();

        // Gender distribution
        $genderDistribution = \App\Models\Patient::selectRaw('gender, COUNT(*) as count')
            ->whereHas('encounters', fn($q) => $q->whereBetween('encounter_date', [$dateFrom, $dateTo]))
            ->groupBy('gender')
            ->get();

        return [
            'new_patients' => $newPatients,
            'total_visits' => $visits,
            'age_distribution' => $ageDistribution,
            'gender_distribution' => $genderDistribution,
        ];
    }
}
