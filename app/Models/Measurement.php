<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Measurement extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'measurements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'device_id',
        'metric_name',
        'value',
        'unit',
        'measured_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'float',
        'measured_at' => 'datetime',
    ];

    /**
     * Get the device that owns the measurement.
     *
     * This defines a many-to-one relationship: many Measurements belong to one Device.
     * It assumes your 'measurements' table has a 'device_id' foreign key.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
