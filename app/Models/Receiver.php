<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receiver extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'mobile_number',
        'national_code',
        'age',
        'gender',
        'province_id',
        'city_id',
        'address',
        'blood_type',
        'rh_factor',
    ];

    /**
     * Get the user that owns the receiver profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the province for the receiver.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the city for the receiver.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the blood requests made by this receiver.
     */
    public function bloodRequests(): HasMany
    {
        return $this->hasMany(BloodRequest::class, 'requested_by', 'user_id');
    }
}
