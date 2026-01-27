<?php

namespace App\Services;

use App\Models\Encounter;
use App\Models\Invoice;
use App\Models\KpiConfig;
use App\Models\KpiSnapshot;
use App\Models\Panel;
use App\Models\PanelClaim;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\QueueTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get executive dashboard data
     */
    public function getExecutiveDashboard(array $filters = []): array
    {
        $dateFrom = $filters['date_from'] ?? now()->startOfMonth();
        $dateTo = $filters['date_to'] ?? now();

        return [
            'summary' => $this->getExecutiveSummary($dateFrom, $dateTo),
            'revenue_trend' => $this->getRevenueTrend($dateFrom, $dateTo),
            'patient_trend' => $this->getPatientTrend($dateFrom, $dateTo),
            'top_services' => $this->getTopServices($dateFrom, $dateTo),
            'payment_methods' => $this->getPaymentMethodDistribution($dateFrom, $dateTo),
            'kpis' => $this->getKpiSummary(),
        ];
    }

    /**
     * Get operational dashboard data
     */
    public function getOperationalDashboard(): array
    {
        $today = now()->toDateString();

        return [
            'today_stats' => $this->getTodayStats(),
            'queue_status' => $this->getQueueStatus(),
            'hourly_visits' => $this->getHourlyVisits($today),
            'staff_status' => $this->getStaffStatus(),
            'recent_transactions' => $this->getRecentTransactions(),
            'alerts' => $this->getOperationalAlerts(),
        ];
    }

    /**
     * Get clinical dashboard data
     */
    public function getClinicalDashboard(array $filters = []): array
    {
        $dateFrom = $filters['date_from'] ?? now()->startOfMonth();
        $dateTo = $filters['date_to'] ?? now();

        return [
            'consultation_stats' => $this->getConsultationStats($dateFrom, $dateTo),
            'diagnosis_distribution' => $this->getDiagnosisDistribution($dateFrom, $dateTo),
            'doctor_performance' => $this->getDoctorPerformance($dateFrom, $dateTo),
            'prescription_stats' => $this->getPrescriptionStats($dateFrom, $dateTo),
        ];
    }

    /**
     * Get pharmacy dashboard data
     */
    public function getPharmacyDashboard(): array
    {
        return [
            'dispensing_stats' => $this->getDispensingStats(),
            'stock_alerts' => $this->getStockAlerts(),
            'expiry_alerts' => $this->getExpiryAlerts(),
            'top_medicines' => $this->getTopMedicines(),
        ];
    }

    /**
     * Executive Summary Metrics
     */
    protected function getExecutiveSummary($dateFrom, $dateTo): array
    {
        // Today's metrics
        $todayRevenue = Payment::whereDate('payment_date', now())
            ->where('status', 'completed')
            ->sum('amount');

        $todayPatients = Encounter::whereDate('encounter_date', now())->count();

        // Period metrics
        $periodRevenue = Payment::whereBetween('payment_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->sum('amount');

        $totalBilled = Invoice::whereBetween('invoice_date', [$dateFrom, $dateTo])
            ->sum('total_amount');

        $collectionRate = $totalBilled > 0 ? ($periodRevenue / $totalBilled) * 100 : 0;

        $periodPatients = Encounter::whereBetween('encounter_date', [$dateFrom, $dateTo])->count();

        // Previous period for comparison
        $periodDays = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1;
        $prevFrom = Carbon::parse($dateFrom)->subDays($periodDays);
        $prevTo = Carbon::parse($dateFrom)->subDay();

        $prevRevenue = Payment::whereBetween('payment_date', [$prevFrom, $prevTo])
            ->where('status', 'completed')
            ->sum('amount');

        $prevPatients = Encounter::whereBetween('encounter_date', [$prevFrom, $prevTo])->count();

        return [
            'today_revenue' => $todayRevenue,
            'today_patients' => $todayPatients,
            'period_revenue' => $periodRevenue,
            'period_patients' => $periodPatients,
            'collection_rate' => round($collectionRate, 1),
            'revenue_change' => $prevRevenue > 0 ? round((($periodRevenue - $prevRevenue) / $prevRevenue) * 100, 1) : 0,
            'patient_change' => $prevPatients > 0 ? round((($periodPatients - $prevPatients) / $prevPatients) * 100, 1) : 0,
            'avg_bill_size' => $periodPatients > 0 ? round($periodRevenue / $periodPatients, 2) : 0,
            'outstanding' => Invoice::where('status', 'unpaid')->sum('total_amount'),
        ];
    }

    /**
     * Revenue trend by day
     */
    protected function getRevenueTrend($dateFrom, $dateTo): array
    {
        $data = Payment::selectRaw('DATE(payment_date) as date, SUM(amount) as total')
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray(),
            'values' => $data->pluck('total')->toArray(),
        ];
    }

    /**
     * Patient visit trend
     */
    protected function getPatientTrend($dateFrom, $dateTo): array
    {
        $data = Encounter::selectRaw('DATE(encounter_date) as date, COUNT(*) as total')
            ->whereBetween('encounter_date', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray(),
            'values' => $data->pluck('total')->toArray(),
        ];
    }

    /**
     * Top services by revenue
     */
    protected function getTopServices($dateFrom, $dateTo, int $limit = 5): array
    {
        return DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->selectRaw('invoice_items.description, SUM(invoice_items.total_price) as total')
            ->whereBetween('invoices.invoice_date', [$dateFrom, $dateTo])
            ->groupBy('invoice_items.description')
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Payment method distribution
     */
    protected function getPaymentMethodDistribution($dateFrom, $dateTo): array
    {
        return Payment::selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->get()
            ->toArray();
    }

    /**
     * Today's operational stats
     */
    protected function getTodayStats(): array
    {
        $today = now()->toDateString();

        return [
            'patients_registered' => Patient::whereDate('created_at', $today)->count(),
            'encounters' => Encounter::whereDate('encounter_date', $today)->count(),
            'completed_encounters' => Encounter::whereDate('encounter_date', $today)
                ->where('status', 'completed')->count(),
            'invoices_created' => Invoice::whereDate('invoice_date', $today)->count(),
            'payments_received' => Payment::whereDate('payment_date', $today)
                ->where('status', 'completed')->count(),
            'total_collected' => Payment::whereDate('payment_date', $today)
                ->where('status', 'completed')->sum('amount'),
        ];
    }

    /**
     * Current queue status
     */
    protected function getQueueStatus(): array
    {
        $today = now()->toDateString();

        $waiting = QueueTicket::whereDate('created_at', $today)
            ->where('status', 'waiting')->count();

        $serving = QueueTicket::whereDate('created_at', $today)
            ->where('status', 'serving')->count();

        $completed = QueueTicket::whereDate('created_at', $today)
            ->where('status', 'completed')->count();

        $avgWaitTime = QueueTicket::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->whereNotNull('called_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, called_at)) as avg_wait')
            ->value('avg_wait');

        return [
            'waiting' => $waiting,
            'serving' => $serving,
            'completed' => $completed,
            'avg_wait_time' => round($avgWaitTime ?? 0, 1),
        ];
    }

    /**
     * Hourly visit distribution
     */
    protected function getHourlyVisits($date): array
    {
        $data = Encounter::whereDate('encounter_date', $date)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $hours = [];
        $counts = [];
        for ($h = 8; $h <= 22; $h++) {
            $hours[] = sprintf('%02d:00', $h);
            $counts[] = $data[$h] ?? 0;
        }

        return ['labels' => $hours, 'values' => $counts];
    }

    /**
     * Staff status (simple implementation)
     */
    protected function getStaffStatus(): array
    {
        // This would integrate with actual staff/attendance system
        return [
            'total_on_duty' => 10,
            'doctors' => 3,
            'nurses' => 4,
            'admin' => 3,
        ];
    }

    /**
     * Recent transactions
     */
    protected function getRecentTransactions(int $limit = 10): array
    {
        return Payment::with(['invoice.patient'])
            ->where('status', 'completed')
            ->latest('payment_date')
            ->limit($limit)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'receipt_no' => $p->receipt_number,
                'patient' => $p->invoice?->patient?->name ?? 'N/A',
                'amount' => $p->amount,
                'method' => $p->payment_method,
                'time' => $p->payment_date->format('H:i'),
            ])
            ->toArray();
    }

    /**
     * Operational alerts
     */
    protected function getOperationalAlerts(): array
    {
        $alerts = [];

        // Long wait time alert
        $avgWait = QueueTicket::whereDate('created_at', now())
            ->where('status', 'waiting')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, NOW())) as avg_wait')
            ->value('avg_wait');

        if ($avgWait > 30) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Masa menunggu purata melebihi 30 minit',
                'value' => round($avgWait) . ' min',
            ];
        }

        // Low collection alert
        $todayTarget = 5000;
        $todayCollected = Payment::whereDate('payment_date', now())
            ->where('status', 'completed')->sum('amount');

        $hourOfDay = now()->hour;
        $expectedProgress = ($hourOfDay - 8) / 10; // Assume 8am-6pm operations
        $expectedAmount = $todayTarget * max(0, $expectedProgress);

        if ($todayCollected < $expectedAmount * 0.7) {
            $alerts[] = [
                'type' => 'danger',
                'message' => 'Kutipan hari ini di bawah sasaran',
                'value' => 'RM ' . number_format($todayCollected, 2),
            ];
        }

        return $alerts;
    }

    /**
     * KPI Summary
     */
    protected function getKpiSummary(): array
    {
        $kpis = KpiConfig::active()->orderBy('sort_order')->get();
        $summary = [];

        foreach ($kpis as $kpi) {
            $value = $this->calculateKpiValue($kpi);
            $summary[] = [
                'code' => $kpi->code,
                'name' => $kpi->name,
                'value' => $value,
                'formatted' => $kpi->getFormattedValue($value),
                'target' => $kpi->target_value,
                'status' => $kpi->getStatus($value),
                'unit' => $kpi->unit,
            ];
        }

        return $summary;
    }

    /**
     * Calculate KPI value dynamically
     */
    public function calculateKpiValue(KpiConfig $kpi): float
    {
        return match($kpi->code) {
            'DAILY_REVENUE' => Payment::whereDate('payment_date', now())
                ->where('status', 'completed')->sum('amount'),
            'MONTHLY_REVENUE' => Payment::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->where('status', 'completed')->sum('amount'),
            'COLLECTION_RATE' => $this->calculateCollectionRate(),
            'DAILY_PATIENTS' => Encounter::whereDate('encounter_date', now())->count(),
            'AVG_WAIT_TIME' => $this->calculateAvgWaitTime(),
            'AVG_CONSULT_TIME' => $this->calculateAvgConsultTime(),
            default => 0,
        };
    }

    protected function calculateCollectionRate(): float
    {
        $billed = Invoice::whereMonth('invoice_date', now()->month)->sum('total_amount');
        $collected = Payment::whereMonth('payment_date', now()->month)
            ->where('status', 'completed')->sum('amount');

        return $billed > 0 ? ($collected / $billed) * 100 : 0;
    }

    protected function calculateAvgWaitTime(): float
    {
        return QueueTicket::whereDate('created_at', now())
            ->where('status', 'completed')
            ->whereNotNull('called_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, called_at)) as avg')
            ->value('avg') ?? 0;
    }

    protected function calculateAvgConsultTime(): float
    {
        return Encounter::whereDate('encounter_date', now())
            ->where('status', 'completed')
            ->whereNotNull('consultation_start')
            ->whereNotNull('consultation_end')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, consultation_start, consultation_end)) as avg')
            ->value('avg') ?? 0;
    }

    /**
     * Consultation statistics
     */
    protected function getConsultationStats($dateFrom, $dateTo): array
    {
        return [
            'total' => Encounter::whereBetween('encounter_date', [$dateFrom, $dateTo])->count(),
            'completed' => Encounter::whereBetween('encounter_date', [$dateFrom, $dateTo])
                ->where('status', 'completed')->count(),
            'avg_duration' => Encounter::whereBetween('encounter_date', [$dateFrom, $dateTo])
                ->where('status', 'completed')
                ->whereNotNull('consultation_start')
                ->whereNotNull('consultation_end')
                ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, consultation_start, consultation_end)) as avg')
                ->value('avg') ?? 0,
        ];
    }

    /**
     * Diagnosis distribution
     */
    protected function getDiagnosisDistribution($dateFrom, $dateTo, int $limit = 10): array
    {
        return DB::table('encounter_diagnoses')
            ->join('encounters', 'encounter_diagnoses.encounter_id', '=', 'encounters.id')
            ->selectRaw('encounter_diagnoses.icd10_code, encounter_diagnoses.description, COUNT(*) as count')
            ->whereBetween('encounters.encounter_date', [$dateFrom, $dateTo])
            ->groupBy('encounter_diagnoses.icd10_code', 'encounter_diagnoses.description')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Doctor performance
     */
    protected function getDoctorPerformance($dateFrom, $dateTo): array
    {
        return Encounter::with('doctor:id,name')
            ->selectRaw('doctor_id, COUNT(*) as total_encounters')
            ->whereBetween('encounter_date', [$dateFrom, $dateTo])
            ->groupBy('doctor_id')
            ->orderByDesc('total_encounters')
            ->get()
            ->map(fn($e) => [
                'doctor' => $e->doctor?->name ?? 'Unknown',
                'encounters' => $e->total_encounters,
            ])
            ->toArray();
    }

    /**
     * Prescription statistics
     */
    protected function getPrescriptionStats($dateFrom, $dateTo): array
    {
        $total = Encounter::whereBetween('encounter_date', [$dateFrom, $dateTo])->count();
        $withPrescription = Encounter::whereBetween('encounter_date', [$dateFrom, $dateTo])
            ->whereHas('prescriptions')->count();

        return [
            'total_encounters' => $total,
            'with_prescription' => $withPrescription,
            'prescription_rate' => $total > 0 ? round(($withPrescription / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Dispensing stats
     */
    protected function getDispensingStats(): array
    {
        $today = now()->toDateString();

        return [
            'pending' => DB::table('dispensing_records')
                ->whereDate('created_at', $today)
                ->where('status', 'pending')->count(),
            'completed' => DB::table('dispensing_records')
                ->whereDate('created_at', $today)
                ->where('status', 'dispensed')->count(),
        ];
    }

    /**
     * Stock alerts
     */
    protected function getStockAlerts(): array
    {
        return DB::table('medicines')
            ->whereRaw('stock_quantity <= reorder_level')
            ->where('is_active', true)
            ->select('id', 'code', 'name', 'stock_quantity', 'reorder_level')
            ->orderBy('stock_quantity')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Expiry alerts
     */
    protected function getExpiryAlerts(): array
    {
        return DB::table('medicines')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->select('id', 'code', 'name', 'expiry_date', 'stock_quantity')
            ->orderBy('expiry_date')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Top medicines
     */
    protected function getTopMedicines(int $limit = 10): array
    {
        return DB::table('dispensing_items')
            ->join('medicines', 'dispensing_items.medicine_id', '=', 'medicines.id')
            ->join('dispensing_records', 'dispensing_items.dispensing_record_id', '=', 'dispensing_records.id')
            ->whereMonth('dispensing_records.dispensed_at', now()->month)
            ->selectRaw('medicines.name, SUM(dispensing_items.quantity_dispensed) as total_qty')
            ->groupBy('medicines.id', 'medicines.name')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
