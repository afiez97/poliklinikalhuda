<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'item_type',
        'item_code',
        'item_name',
        'description',
        'quantity',
        'unit_price',
        'discount_amount',
        'line_total',
        'is_taxable',
        'billable_type',
        'billable_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
        'is_taxable' => 'boolean',
    ];

    /**
     * Item type labels.
     */
    public const ITEM_TYPE_LABELS = [
        'consultation' => 'Konsultasi',
        'medication' => 'Ubat',
        'procedure' => 'Prosedur',
        'lab_test' => 'Ujian Makmal',
        'package' => 'Pakej',
        'other' => 'Lain-lain',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->line_total = ($item->quantity * $item->unit_price) - $item->discount_amount;
        });
    }

    /**
     * Get the invoice.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the billable model.
     */
    public function billable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get item type label.
     */
    public function getItemTypeLabelAttribute(): string
    {
        return self::ITEM_TYPE_LABELS[$this->item_type] ?? $this->item_type;
    }

    /**
     * Get gross amount (before item discount).
     */
    public function getGrossAmountAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }
}
