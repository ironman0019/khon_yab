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

    /**
     * Calculate the next eligible donation date based on last donation.
     */
    public function nextEligibleDonationDate(?int $donationType = 0): ?\Illuminate\Support\Carbon
    {
        if (!$this->last_donation_date) {
            return now();
        }

        $minDays = match ($donationType) {
            0 => 56, // Whole Blood - 8 weeks
            1 => 28, // Plasma - 4 weeks
            2 => 7,  // Platelets - 1 week
            default => 56,
        };

        return $this->last_donation_date->copy()->addDays($minDays);
    }

    /**
     * Check if the donor can donate now.
     */
    public function canDonate(?int $donationType = 0): bool
    {
        if (!$this->ability_to_donate || !$this->health_status) {
            return false;
        }

        if (!$this->last_donation_date) {
            return true;
        }

        $nextEligibleDate = $this->nextEligibleDonationDate($donationType);
        return now()->greaterThanOrEqualTo($nextEligibleDate);
    }

    /**
     * Get days until next eligible donation.
     */
    public function daysUntilNextDonation(?int $donationType = 0): ?int
    {
        if (!$this->last_donation_date) {
            return 0;
        }

        $nextEligibleDate = $this->nextEligibleDonationDate($donationType);
        
        if (now()->greaterThanOrEqualTo($nextEligibleDate)) {
            return 0;
        }

        return now()->diffInDays($nextEligibleDate);
    }
}
