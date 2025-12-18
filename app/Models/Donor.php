<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donor extends Model
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
        'health_status',
        'last_donation_date',
        'ability_to_donate',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_donation_date' => 'date',
            'health_status' => 'boolean',
            'ability_to_donate' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the donor profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the province for the donor.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the city for the donor.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the blood donation records for the donor.
     */
    public function bloodDonationRecords(): HasMany
    {
        return $this->hasMany(BloodDonationRecord::class);
    }
}
