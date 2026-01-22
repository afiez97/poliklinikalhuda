<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GLUtilization extends Model
{
    use HasFactory;

    protected $table = 'gl_utilizations';

    protected $fillable = [
        'guarantee_letter_id',
        'invoice_id',
        'encounter_id',
        'utilization_date',
        'amount',
        'running_balance',
        'reference_type',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'utilization_date' => 'date',
        'amount' => 'decimal:2',
        'running_balance' => 'decimal:2',
    ];

    public const TYPE_INVOICE = 'invoice';

    public const TYPE_ADJUSTMENT = 'adjustment';

    public const TYPE_REFUND = 'refund';

    public function guaranteeLetter(): BelongsTo
    {
        return $this->belongsTo(GuaranteeLetter::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
