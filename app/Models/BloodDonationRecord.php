<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BloodDonationRecord extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'donor_id',
        'donation_type',
        'amount_ml',
        'donation_date',
        'expiration_date',
        'status',
        'recorded_by_admin',
        'submitted_by_donor',
        'province_id',
        'city_id',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'donation_date' => 'date',
            'expiration_date' => 'date',
            'status' => 'integer',
            'submitted_by_donor' => 'boolean',
        ];
    }

    /**
     * Get the donor that made the donation.
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Get the admin who recorded the donation.
     */
    public function recordedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_admin');
    }

    /**
     * Get the blood test for this donation record.
     */
    public function bloodTest(): HasOne
    {
        return $this->hasOne(BloodTest::class);
    }

    /**
     * Get the blood inventory entries for this donation record.
     */
    public function bloodInventory(): HasMany
    {
        return $this->hasMany(BloodInventory::class);
    }

    /**
     * Get the province where the donation took place.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the city where the donation took place.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
