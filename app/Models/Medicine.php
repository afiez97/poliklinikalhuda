<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_code',
        'name',
        'description',
        'category',
        'manufacturer',
        'strength',
        'unit_price',
        'stock_quantity',
        'minimum_stock',
        'expiry_date',
        'batch_number',
        'status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    /**
     * Auto-generate medicine code jika tidak diisi
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($medicine) {
            if (empty($medicine->medicine_code)) {
                $prefix = config('medicine.code.prefix', 'MED');
                $length = config('medicine.code.length', 6);
                $padString = config('medicine.code.pad_string', '0');

                $lastId = static::max('id') ?? 0;
                $medicine->medicine_code = $prefix . str_pad($lastId + 1, $length, $padString, STR_PAD_LEFT);
            }
        });
    }

    /**
     * Scope untuk ubat aktif sahaja
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk ubat yang stok rendah
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock_quantity <= minimum_stock')
                     ->where('status', 'active');
    }

    /**
     * Scope untuk ubat yang hampir luput (dalam 30 hari)
     */
    public function scopeExpiringSoon($query)
    {
        return $query->where('expiry_date', '<=', Carbon::now()->addDays(30))
                     ->where('expiry_date', '>=', Carbon::now())
                     ->where('status', 'active');
    }

    /**
     * Scope untuk ubat yang sudah luput
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', Carbon::now());
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = config('medicine.status_badges', []);
        return $badges[$this->status] ?? '<span class="badge badge-secondary">Tidak Diketahui</span>';
    }

    /**
     * Accessor untuk category label
     */
    public function getCategoryLabelAttribute()
    {
        $categories = config('medicine.category_labels', []);
        return $categories[$this->category] ?? $this->category;
    }

    /**
     * Accessor untuk stock status
     */
    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity <= 0) {
            return '<span class="badge badge-danger">Habis</span>';
        } elseif ($this->stock_quantity <= $this->minimum_stock) {
            return '<span class="badge badge-warning">Stok Rendah</span>';
        } else {
            return '<span class="badge badge-success">Mencukupi</span>';
        }
    }

    /**
     * Accessor untuk expiry status
     */
    public function getExpiryStatusAttribute()
    {
        if (!$this->expiry_date) {
            return '<span class="badge badge-secondary">Tiada Tarikh Luput</span>';
        }

        $daysToExpiry = Carbon::now()->diffInDays($this->expiry_date, false);

        if ($daysToExpiry < 0) {
            return '<span class="badge badge-danger">Sudah Luput</span>';
        } elseif ($daysToExpiry <= 30) {
            return '<span class="badge badge-warning">Hampir Luput (' . abs($daysToExpiry) . ' hari)</span>';
        } else {
            return '<span class="badge badge-success">Masih Segar</span>';
        }
    }

    /**
     * Check jika ubat ini stok rendah
     */
    public function isLowStock()
    {
        return $this->stock_quantity <= $this->minimum_stock;
    }

    /**
     * Check jika ubat ini hampir luput
     */
    public function isExpiringSoon()
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date <= Carbon::now()->addDays(30) &&
               $this->expiry_date >= Carbon::now();
    }

    /**
     * Check jika ubat ini sudah luput
     */
    public function isExpired()
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date < Carbon::now();
    }

    /**
     * Accessor untuk total value
     */
    public function getTotalValueAttribute()
    {
        return $this->stock_quantity * $this->unit_price;
    }
}
