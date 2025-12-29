<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laboratory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'laboratory_name',
        'laboratory_code',
        'mobile_number',
        'phone_number',
        'province_id',
        'city_id',
        'address',
        'license_number',
        'contact_person_name',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'integer',
        ];
    }

    /**
     * Get the user that owns the laboratory profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the province for the laboratory.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the city for the laboratory.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the blood requests for the laboratory.
     */
    public function bloodRequests(): HasMany
    {
        return $this->hasMany(BloodRequest::class, 'requested_by', 'user_id');
    }

    /**
     * Get the blood donation records recorded by this laboratory.
     */
    public function bloodDonationRecords(): HasMany
    {
        return $this->hasMany(BloodDonationRecord::class, 'recorded_by_admin', 'user_id');
    }
}
