<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingSetting;
use App\Models\CashierClosing;
use App\Models\DiscountApproval;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\PromoCode;
use App\Models\Refund;
use App\Services\AuditService;
use App\Services\CashierService;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use App\Services\RefundService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Put;

#[Prefix('admin/billing')]
#[Middleware(['web', 'auth'])]
class BillingController extends Controller
{
    public function __construct(
        protected InvoiceService $invoiceService,
        protected PaymentService $paymentService,
        protected RefundService $refundService,
        protected CashierService $cashierService,
        protected AuditService $auditService
    ) {}

    /**
     * Billing dashboard.
     */
    #[Get('/', name: 'admin.billing.index')]
    public function index()
    {
        $todaySummary = $this->paymentService->getDailySummary();
        $outstandingSummary = $this->invoiceService->getOutstandingSummary();
        $pendingRefunds = $this->refundService->getPendingCount();
        $pendingDiscounts = DiscountApproval::pending()->count();

        $recentInvoices = Invoice::with(['patient', 'payments'])
            ->latest('invoice_date')
            ->take(10)
            ->get();

        $recentPayments = Payment::with(['invoice', 'invoice.patient'])
            ->where('status', 'completed')
            ->latest('payment_date')
            ->take(10)
            ->get();

        return view('admin.billing.index', compact(
            'todaySummary',
            'outstandingSummary',
            'pendingRefunds',
            'pendingDiscounts',
            'recentInvoices',
            'recentPayments'
        ));
    }

    // ==================== INVOICES ====================

    /**
     * List invoices.
     */
    #[Get('/invoices', name: 'admin.billing.invoices.index')]
    public function invoices(Request $request)
    {
        $invoices = $this->invoiceService->getPaginated($request->all());

        return view('admin.billing.invoices.index', compact('invoices'));
    }

    /**
     * Show invoice.
     */
    #[Get('/invoices/{invoice}', name: 'admin.billing.invoices.show')]
    public function showInvoice(Invoice $invoice)
    {
        $invoice->load(['patient', 'items', 'payments', 'payments.receipt', 'promoCode']);

        return view('admin.billing.invoices.show', compact('invoice'));
    }

    /**
     * Create manual invoice form.
     */
    #[Get('/invoices/create', name: 'admin.billing.invoices.create')]
    public function createInvoice(Request $request)
    {
        $patients = Patient::orderBy('name')->get();
        $packages = Package::active()->get();
        $promoCodes = PromoCode::active()->get();

        $selectedPatient = null;
        if ($request->has('patient_id')) {
            $selectedPatient = Patient::find($request->patient_id);
        }

        return view('admin.billing.invoices.create', compact(
            'patients',
            'packages',
            'promoCodes',
            'selectedPatient'
        ));
    }

    /**
     * Store manual invoice.
     */
    #[Post('/invoices', name: 'admin.billing.invoices.store')]
    public function storeInvoice(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|string',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            $invoice = $this->invoiceService->createManual(
                $request->all(),
                Auth::id()
            );

            return redirect()
                ->route('admin.billing.invoices.show', $invoice)
                ->with('success', 'Invois berjaya dicipta');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Apply promo code to invoice.
     */
    #[Post('/invoices/{invoice}/promo', name: 'admin.billing.invoices.apply-promo')]
    public function applyPromoCode(Request $request, Invoice $invoice)
    {
        $request->validate([
            'promo_code' => 'required|string',
        ]);

        $result = $this->invoiceService->applyPromoCode($invoice, $request->promo_code);

        if (is_string($result)) {
            return back()->with('error', $result);
        }

        return back()->with('success', 'Kod promo berjaya digunakan');
    }

    /**
     * Request discount approval.
     */
    #[Post('/invoices/{invoice}/discount', name: 'admin.billing.invoices.request-discount')]
    public function requestDiscount(Request $request, Invoice $invoice)
    {
        $request->validate([
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'reason' => 'required|string|max:500',
        ]);

        $discountAmount = $request->discount_type === 'percentage'
            ? $invoice->subtotal * ($request->discount_value / 100)
            : $request->discount_value;

        // Check threshold
        $threshold = BillingSetting::getDiscountApprovalThreshold();

        if ($request->discount_type === 'percentage' && $request->discount_value <= $threshold) {
            // Auto-approve small discounts
            $this->invoiceService->applyDiscount(
                $invoice,
                $request->discount_type,
                $request->discount_value,
                Auth::id()
            );

            return back()->with('success', 'Diskaun berjaya digunakan');
        }

        // Create approval request
        DiscountApproval::create([
            'invoice_id' => $invoice->id,
            'requested_by' => Auth::id(),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'discount_amount' => $discountAmount,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Permohonan diskaun telah dihantar untuk kelulusan');
    }

    /**
     * Void invoice.
     */
    #[Post('/invoices/{invoice}/void', name: 'admin.billing.invoices.void')]
    public function voidInvoice(Request $request, Invoice $invoice)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $this->invoiceService->void($invoice, $request->reason, Auth::id());

            return back()->with('success', 'Invois berjaya dibatalkan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Print invoice.
     */
    #[Get('/invoices/{invoice}/print', name: 'admin.billing.invoices.print')]
    public function printInvoice(Invoice $invoice)
    {
        $invoice->load(['patient', 'items', 'payments']);

        return view('admin.billing.invoices.print', compact('invoice'));
    }

    // ==================== PAYMENTS ====================

    /**
     * List payments.
     */
    #[Get('/payments', name: 'admin.billing.payments.index')]
    public function payments(Request $request)
    {
        $payments = $this->paymentService->getPaginated($request->all());

        return view('admin.billing.payments.index', compact('payments'));
    }

    /**
     * Payment form for invoice.
     */
    #[Get('/invoices/{invoice}/pay', name: 'admin.billing.invoices.pay')]
    public function paymentForm(Invoice $invoice)
    {
        $invoice->load(['patient', 'items', 'payments']);

        if ($invoice->balance <= 0) {
            return redirect()
                ->route('admin.billing.invoices.show', $invoice)
                ->with('error', 'Invois ini telah dibayar sepenuhnya');
        }

        return view('admin.billing.payments.create', compact('invoice'));
    }

    /**
     * Process payment.
     */
    #[Post('/invoices/{invoice}/pay', name: 'admin.billing.invoices.process-payment')]
    public function processPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,card,qr,ewallet,transfer,panel',
            'amount' => 'required|numeric|min:0.01|max:'.$invoice->balance,
            'reference_number' => 'nullable|string|max:100',
            'card_type' => 'nullable|required_if:payment_method,card|string',
            'card_last_four' => 'nullable|string|max:4',
            'bank_name' => 'nullable|string|max:100',
        ]);

        try {
            $payment = $this->paymentService->processPayment(
                $invoice,
                $request->all(),
                Auth::id()
            );

            return redirect()
                ->route('admin.billing.receipts.show', $payment->receipt)
                ->with('success', 'Pembayaran berjaya direkod');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Void payment.
     */
    #[Post('/payments/{payment}/void', name: 'admin.billing.payments.void')]
    public function voidPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $this->paymentService->voidPayment($payment, $request->reason, Auth::id());

            return back()->with('success', 'Pembayaran berjaya dibatalkan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ==================== RECEIPTS ====================

    /**
     * Show receipt.
     */
    #[Get('/receipts/{receipt}', name: 'admin.billing.receipts.show')]
    public function showReceipt(\App\Models\Receipt $receipt)
    {
        $receipt->load(['payment', 'invoice', 'invoice.patient']);

        return view('admin.billing.receipts.show', compact('receipt'));
    }

    /**
     * Print receipt.
     */
    #[Get('/receipts/{receipt}/print', name: 'admin.billing.receipts.print')]
    public function printReceipt(\App\Models\Receipt $receipt)
    {
        $receipt->load(['payment', 'invoice', 'invoice.patient']);

        $receipt->markAsPrinted();

        return view('admin.billing.receipts.print', compact('receipt'));
    }

    // ==================== REFUNDS ====================

    /**
     * List refunds.
     */
    #[Get('/refunds', name: 'admin.billing.refunds.index')]
    public function refunds(Request $request)
    {
        $refunds = $this->refundService->getPaginated($request->all());

        return view('admin.billing.refunds.index', compact('refunds'));
    }

    /**
     * Request refund form.
     */
    #[Get('/payments/{payment}/refund', name: 'admin.billing.refunds.create')]
    public function refundForm(Payment $payment)
    {
        $payment->load(['invoice', 'invoice.patient']);

        $refundableAmount = $this->refundService->getRefundableAmount($payment);

        if ($refundableAmount <= 0) {
            return back()->with('error', 'Pembayaran ini tidak boleh dipulangkan');
        }

        return view('admin.billing.refunds.create', compact('payment', 'refundableAmount'));
    }

    /**
     * Request refund.
     */
    #[Post('/payments/{payment}/refund', name: 'admin.billing.refunds.store')]
    public function requestRefund(Request $request, Payment $payment)
    {
        $refundableAmount = $this->refundService->getRefundableAmount($payment);

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:'.$refundableAmount,
            'reason' => 'required|string|max:500',
            'refund_method' => 'required|in:cash,card,transfer',
        ]);

        try {
            $refund = $this->refundService->requestRefund(
                $payment,
                $request->all(),
                Auth::id()
            );

            $message = $refund->isPending()
                ? 'Permohonan pulangan dihantar untuk kelulusan'
                : 'Permohonan pulangan diluluskan';

            return redirect()
                ->route('admin.billing.refunds.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Approve refund.
     */
    #[Post('/refunds/{refund}/approve', name: 'admin.billing.refunds.approve')]
    public function approveRefund(Refund $refund)
    {
        try {
            $this->refundService->approveRefund($refund, Auth::id());

            return back()->with('success', 'Pulangan diluluskan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject refund.
     */
    #[Post('/refunds/{refund}/reject', name: 'admin.billing.refunds.reject')]
    public function rejectRefund(Request $request, Refund $refund)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            $this->refundService->rejectRefund($refund, $request->rejection_reason, Auth::id());

            return back()->with('success', 'Pulangan ditolak');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Process refund.
     */
    #[Post('/refunds/{refund}/process', name: 'admin.billing.refunds.process')]
    public function processRefund(Request $request, Refund $refund)
    {
        $request->validate([
            'reference_number' => 'nullable|string|max:100',
        ]);

        try {
            $this->refundService->processRefund($refund, $request->all(), Auth::id());

            return back()->with('success', 'Pulangan berjaya diproses');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ==================== CASHIER ====================

    /**
     * Cashier closing list.
     */
    #[Get('/cashier', name: 'admin.billing.cashier.index')]
    public function cashierClosings(Request $request)
    {
        $closings = $this->cashierService->getClosings($request->all());
        $liveTotals = $this->cashierService->getLiveSessionTotals(Auth::id());
        $todaySession = $this->cashierService->getTodaySession(Auth::id());

        return view('admin.billing.cashier.index', compact(
            'closings',
            'liveTotals',
            'todaySession'
        ));
    }

    /**
     * Start cashier session.
     */
    #[Post('/cashier/start', name: 'admin.billing.cashier.start')]
    public function startCashierSession(Request $request)
    {
        $request->validate([
            'opening_balance' => 'nullable|numeric|min:0',
        ]);

        $session = $this->cashierService->startSession(
            Auth::id(),
            $request->opening_balance
        );

        return back()->with('success', 'Sesi kaunter dimulakan');
    }

    /**
     * Close cashier session.
     */
    #[Post('/cashier/close', name: 'admin.billing.cashier.close')]
    public function closeCashierSession(Request $request)
    {
        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $session = $this->cashierService->getTodaySession(Auth::id());

        if (! $session) {
            return back()->with('error', 'Tiada sesi kaunter aktif');
        }

        try {
            $this->cashierService->closeSession(
                $session,
                $request->actual_cash,
                $request->notes
            );

            return back()->with('success', 'Sesi kaunter ditutup dan dihantar untuk pengesahan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Verify cashier closing.
     */
    #[Post('/cashier/{closing}/verify', name: 'admin.billing.cashier.verify')]
    public function verifyCashierClosing(CashierClosing $closing)
    {
        try {
            $this->cashierService->verifyClosing($closing, Auth::id());

            return back()->with('success', 'Tutup kaunter disahkan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ==================== DISCOUNT APPROVALS ====================

    /**
     * List pending discount approvals.
     */
    #[Get('/approvals', name: 'admin.billing.approvals.index')]
    public function discountApprovals()
    {
        $approvals = DiscountApproval::with(['invoice', 'invoice.patient', 'requestedBy'])
            ->pending()
            ->latest()
            ->paginate(15);

        return view('admin.billing.approvals.index', compact('approvals'));
    }

    /**
     * Approve discount.
     */
    #[Post('/approvals/{approval}/approve', name: 'admin.billing.approvals.approve')]
    public function approveDiscount(DiscountApproval $approval)
    {
        try {
            $approval->approve(Auth::id());

            return back()->with('success', 'Diskaun diluluskan dan digunakan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject discount.
     */
    #[Post('/approvals/{approval}/reject', name: 'admin.billing.approvals.reject')]
    public function rejectDiscount(Request $request, DiscountApproval $approval)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            $approval->reject(Auth::id(), $request->rejection_reason);

            return back()->with('success', 'Permohonan diskaun ditolak');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ==================== REPORTS ====================

    /**
     * Billing reports dashboard.
     */
    #[Get('/reports', name: 'admin.billing.reports')]
    public function reports(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $monthlySummary = $this->paymentService->getMonthlySummary(
            now()->year,
            now()->month
        );

        $outstandingSummary = $this->invoiceService->getOutstandingSummary();
        $refundStats = $this->refundService->getStatistics($startDate, $endDate);

        return view('admin.billing.reports.index', compact(
            'startDate',
            'endDate',
            'monthlySummary',
            'outstandingSummary',
            'refundStats'
        ));
    }

    /**
     * Daily collection report.
     */
    #[Get('/reports/daily', name: 'admin.billing.reports.daily')]
    public function dailyReport(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        $summary = $this->paymentService->getDailySummary(
            \Carbon\Carbon::parse($date)
        );

        $payments = Payment::with(['invoice', 'invoice.patient', 'receipt', 'receivedBy'])
            ->whereDate('payment_date', $date)
            ->where('status', 'completed')
            ->orderBy('created_at')
            ->get();

        return view('admin.billing.reports.daily', compact('date', 'summary', 'payments'));
    }

    /**
     * Outstanding report.
     */
    #[Get('/reports/outstanding', name: 'admin.billing.reports.outstanding')]
    public function outstandingReport(Request $request)
    {
        $invoices = Invoice::with(['patient'])
            ->where('balance', '>', 0)
            ->whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PARTIAL])
            ->orderBy('due_date')
            ->paginate(20);

        $summary = $this->invoiceService->getOutstandingSummary();

        return view('admin.billing.reports.outstanding', compact('invoices', 'summary'));
    }

    // ==================== SETTINGS ====================

    /**
     * Billing settings.
     */
    #[Get('/settings', name: 'admin.billing.settings')]
    public function settings()
    {
        $settings = BillingSetting::getAllSettings();

        return view('admin.billing.settings', compact('settings'));
    }

    /**
     * Update billing settings.
     */
    #[Put('/settings', name: 'admin.billing.settings.update')]
    public function updateSettings(Request $request)
    {
        $request->validate([
            'sst_rate' => 'required|numeric|min:0|max:100',
            'sst_enabled' => 'boolean',
            'rounding_enabled' => 'boolean',
            'rounding_precision' => 'required|integer|in:5,10',
            'discount_approval_threshold' => 'required|numeric|min:0|max:100',
            'max_discount_percentage' => 'required|numeric|min:0|max:100',
            'payment_terms_days' => 'required|integer|min:0|max:365',
            'default_opening_balance' => 'required|numeric|min:0',
        ]);

        foreach ($request->except('_token', '_method') as $key => $value) {
            BillingSetting::setValue($key, $value);
        }

        $this->auditService->log('billing_settings', 'update', null, $request->all());

        return back()->with('success', 'Tetapan berjaya dikemaskini');
    }
}
