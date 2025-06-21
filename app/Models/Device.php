<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Device
 *
 * @package App\Models
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $location
 * @property string|null $description
 * @property string $device_group
 * @property string|null $webclient_start_url
 * @property \Illuminate\Support\Carbon|null $last_callback_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Measurement> $measurements
 * @property-read int|null $measurements_count
 */
class Device extends Model
{
    use HasFactory;

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
        'location',
        'description',
        'webclient_start_url',
        // 'user_id' is intentionally not fillable here based on the claiming logic.
        // 'last_callback_at' is updated programmatically.
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_callback_at' => 'datetime', // Cast the timestamp column to a Carbon instance
    ];

    /**
     * Get the user that owns the device.
     *
     * This defines a many-to-one relationship: many Devices belong to one User.
     * The relationship can be null if user_id is null.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the group that owns the device.
     *
     * This defines a many-to-one relationship: many Devices belong to one Group.
     */
    public function device_group(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
