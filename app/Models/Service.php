<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'tanggal_masuk',
        'keluhan',
        'biaya_jasa',
        'biaya_sparepart',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_masuk' => 'date',
            'biaya_jasa' => 'decimal:2',
            'biaya_sparepart' => 'decimal:2',
        ];
    }

    protected function totalBiaya(): Attribute
    {
        return Attribute::make(
            get: fn () => (float) $this->biaya_jasa
                + (float) $this->biaya_sparepart,
        );
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
