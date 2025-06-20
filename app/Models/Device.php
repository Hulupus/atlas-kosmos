<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'devices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the measurements associated with the device.
     *
     * This defines a one-to-many relationship: one Device can have many Measurements.
     * It assumes your 'measurements' table has a 'device_id' foreign key.
     */
    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }
}
