<?php

namespace App\Services;

use App\Models\BillingSetting;
use App\Models\CashierClosing;
use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CashierService
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    /**
     * Get paginated cashier closings.
     */
    public function getClosings(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = CashierClosing::with(['cashier', 'verifiedBy']);

        if (! empty($filters['cashier_id'])) {
            $query->where('cashier_id', $filters['cashier_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('closing_date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('closing_date', '<=', $filters['date_to']);
        }

        if (! empty($filters['has_discrepancy'])) {
            $query->where('cash_difference', '!=', 0);
        }

        return $query->latest('closing_date')->paginate($perPage);
    }

    /**
     * Start new cashier session.
     */
    public function startSession(int $cashierId, ?float $openingBalance = null): CashierClosing
    {
        // Check if already has open session today
        $existingSession = CashierClosing::forDate(today())
            ->forCashier($cashierId)
            ->where('status', CashierClosing::STATUS_DRAFT)
            ->first();

        if ($existingSession) {
            return $existingSession;
        }

        $openingBalance = $openingBalance ?? BillingSetting::getDefaultOpeningBalance();

        $session = CashierClosing::create([
            'closing_date' => today(),
            'cashier_id' => $cashierId,
            'opening_balance' => $openingBalance,
            'status' => CashierClosing::STATUS_DRAFT,
        ]);

        $this->auditService->log(
            'cashier',
            'start_session',
            $session,
            ['opening_balance' => $openingBalance]
        );

        return $session;
    }

    /**
     * Calculate current session totals.
     */
    public function calculateSessionTotals(CashierClosing $session): CashierClosing
    {
        $session->calculateFromPayments();
        $session->save();

        return $session;
    }

    /**
     * Close cashier session.
     */
    public function closeSession(CashierClosing $session, float $actualCash, ?string $notes = null): CashierClosing
    {
        return DB::transaction(function () use ($session, $actualCash, $notes) {
            // Calculate totals
            $session->calculateFromPayments();

            // Set actual cash and calculate difference
            $session->setActualCash($actualCash);
            $session->notes = $notes;

            // Submit for verification
            $session->submit();

            $this->auditService->log(
                'cashier',
                'close_session',
                $session,
                [
                    'actual_cash' => $actualCash,
                    'expected_cash' => $session->expected_cash,
                    'difference' => $session->cash_difference,
                ]
            );

            return $session;
        });
    }

    /**
     * Verify cashier closing.
     */
    public function verifyClosing(CashierClosing $session, int $verifiedBy): CashierClosing
    {
        if ($session->status !== CashierClosing::STATUS_SUBMITTED) {
            throw new \Exception('Hanya tutup kaunter yang dihantar boleh disahkan');
        }

        $session->verify($verifiedBy);

        $this->auditService->log(
            'cashier',
            'verify_closing',
            $session,
            ['verified_by' => $verifiedBy]
        );

        return $session;
    }

    /**
     * Get today's session for cashier.
     */
    public function getTodaySession(int $cashierId): ?CashierClosing
    {
        return CashierClosing::forDate(today())
            ->forCashier($cashierId)
            ->first();
    }

    /**
     * Get live session totals.
     */
    public function getLiveSessionTotals(int $cashierId): array
    {
        $payments = Payment::whereDate('created_at', today())
            ->where('status', Payment::STATUS_COMPLETED)
            ->get();

        $refunds = Refund::whereDate('processed_at', today())
            ->where('status', Refund::STATUS_PROCESSED)
            ->sum('amount');

        $session = $this->getTodaySession($cashierId);
        $openingBalance = $session?->opening_balance ?? BillingSetting::getDefaultOpeningBalance();

        $cashSales = $payments->where('payment_method', 'cash')->sum('amount');

        return [
            'opening_balance' => $openingBalance,
            'cash_sales' => $cashSales,
            'card_sales' => $payments->where('payment_method', 'card')->sum('amount'),
            'qr_sales' => $payments->where('payment_method', 'qr')->sum('amount'),
            'ewallet_sales' => $payments->where('payment_method', 'ewallet')->sum('amount'),
            'transfer_sales' => $payments->where('payment_method', 'transfer')->sum('amount'),
            'panel_sales' => $payments->where('payment_method', 'panel')->sum('amount'),
            'total_sales' => $payments->sum('amount'),
            'total_refunds' => $refunds,
            'net_sales' => $payments->sum('amount') - $refunds,
            'expected_cash' => $openingBalance + $cashSales,
            'transaction_count' => $payments->count(),
        ];
    }

    /**
     * Get discrepancy report.
     */
    public function getDiscrepancyReport($startDate, $endDate): array
    {
        $closings = CashierClosing::whereBetween('closing_date', [$startDate, $endDate])
            ->where('status', CashierClosing::STATUS_VERIFIED)
            ->where('cash_difference', '!=', 0)
            ->with('cashier')
            ->get();

        $totalOver = $closings->where('cash_difference', '>', 0)->sum('cash_difference');
        $totalShort = $closings->where('cash_difference', '<', 0)->sum('cash_difference');

        return [
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'total_discrepancies' => $closings->count(),
            'total_over' => $totalOver,
            'total_short' => abs($totalShort),
            'net_discrepancy' => $totalOver + $totalShort,
            'by_cashier' => $closings->groupBy('cashier_id')->map(function ($group) {
                $cashier = $group->first()->cashier;

                return [
                    'cashier_name' => $cashier?->name ?? 'Unknown',
                    'count' => $group->count(),
                    'total_over' => $group->where('cash_difference', '>', 0)->sum('cash_difference'),
                    'total_short' => abs($group->where('cash_difference', '<', 0)->sum('cash_difference')),
                ];
            })->values()->toArray(),
            'details' => $closings->map(function ($closing) {
                return [
                    'date' => $closing->closing_date->format('Y-m-d'),
                    'cashier' => $closing->cashier?->name ?? 'Unknown',
                    'expected' => $closing->expected_cash,
                    'actual' => $closing->actual_cash,
                    'difference' => $closing->cash_difference,
                    'notes' => $closing->notes,
                ];
            })->toArray(),
        ];
    }

    /**
     * Get cashier performance summary.
     */
    public function getCashierPerformance(int $cashierId, $startDate, $endDate): array
    {
        $closings = CashierClosing::forCashier($cashierId)
            ->whereBetween('closing_date', [$startDate, $endDate])
            ->where('status', CashierClosing::STATUS_VERIFIED)
            ->get();

        $payments = Payment::where('received_by', $cashierId)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', Payment::STATUS_COMPLETED)
            ->get();

        return [
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'sessions_count' => $closings->count(),
            'total_collected' => $payments->sum('amount'),
            'transactions_count' => $payments->count(),
            'average_transaction' => $payments->count() > 0 ? $payments->sum('amount') / $payments->count() : 0,
            'discrepancy_count' => $closings->where('cash_difference', '!=', 0)->count(),
            'total_discrepancy' => $closings->sum('cash_difference'),
            'accuracy_rate' => $closings->count() > 0
                ? (($closings->count() - $closings->where('cash_difference', '!=', 0)->count()) / $closings->count()) * 100
                : 100,
        ];
    }
}
