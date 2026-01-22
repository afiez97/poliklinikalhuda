<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentAdvice extends Model
{
    use HasFactory;

    protected $table = 'payment_advices';

    protected $fillable = [
        'panel_id',
        'advice_number',
        'advice_date',
        'payment_date',
        'payment_reference',
        'payment_method',
        'total_amount',
        'claim_count',
        'file_path',
        'remarks',
        'status',
        'uploaded_by',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'advice_date' => 'date',
        'payment_date' => 'date',
        'total_amount' => 'decimal:2',
        'claim_count' => 'integer',
        'processed_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING => 'Menunggu',
        self::STATUS_PROCESSING => 'Diproses',
        self::STATUS_COMPLETED => 'Selesai',
        self::STATUS_CANCELLED => 'Dibatalkan',
    ];

    public const METHOD_CHEQUE = 'cheque';

    public const METHOD_BANK_TRANSFER = 'bank_transfer';

    public const METHOD_ONLINE = 'online';

    public const METHOD_CASH = 'cash';

    public const METHODS = [
        self::METHOD_CHEQUE => 'Cek',
        self::METHOD_BANK_TRANSFER => 'Pemindahan Bank',
        self::METHOD_ONLINE => 'Pembayaran Online',
        self::METHOD_CASH => 'Tunai',
    ];

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function reconciliations(): HasMany
    {
        return $this->hasMany(PaymentReconciliation::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function getStatusNameAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getMethodNameAttribute(): ?string
    {
        return self::METHODS[$this->payment_method] ?? $this->payment_method;
    }

    public function getMatchedCountAttribute(): int
    {
        return $this->reconciliations()->where('match_status', 'matched')->count();
    }

    public function getDiscrepancyCountAttribute(): int
    {
        return $this->reconciliations()->whereIn('match_status', ['short_payment', 'over_payment'])->count();
    }

    public function markAsProcessed(int $userId): void
    {
        $this->status = self::STATUS_COMPLETED;
        $this->processed_by = $userId;
        $this->processed_at = now();
        $this->save();
    }
}
