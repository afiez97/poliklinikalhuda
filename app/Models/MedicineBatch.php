<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicineBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'batch_no',
        'manufacturing_date',
        'expiry_date',
        'initial_quantity',
        'current_quantity',
        'cost_price',
        'supplier_id',
        'purchase_order_id',
        'status',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'manufacturing_date' => 'date',
            'expiry_date' => 'date',
            'cost_price' => 'decimal:2',
        ];
    }

    public const STATUS_ACTIVE = 'active';

    public const STATUS_LOW = 'low';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_DEPLETED = 'depleted';

    public const STATUSES = [
        'active' => 'Aktif',
        'low' => 'Stok Rendah',
        'expired' => 'Luput',
        'depleted' => 'Habis',
    ];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeAvailable($query)
    {
        return $query->where('current_quantity', '>', 0)
            ->where('expiry_date', '>', now());
    }

    public function scopeExpiringSoon($query, int $days = 90)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now());
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 90): bool
    {
        return $this->expiry_date
            && $this->expiry_date->isFuture()
            && $this->expiry_date->diffInDays(now()) <= $days;
    }

    public function isDepleted(): bool
    {
        return $this->current_quantity <= 0;
    }

    public function updateStatus(): void
    {
        if ($this->isExpired()) {
            $this->status = self::STATUS_EXPIRED;
        } elseif ($this->isDepleted()) {
            $this->status = self::STATUS_DEPLETED;
        } elseif ($this->current_quantity <= ($this->initial_quantity * 0.2)) {
            $this->status = self::STATUS_LOW;
        } else {
            $this->status = self::STATUS_ACTIVE;
        }
        $this->save();
    }
}
