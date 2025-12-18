<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BloodRequest extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'requested_by',
        'blood_type',
        'rh_factor',
        'number_of_bags',
        'patient_name',
        'patient_age',
        'request_reason',
        'contact_number',
        'province_id',
        'city_id',
        'medical_center',
        'status',
        'approved_by',
        'approval_date',
        'rejection_reason',
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
            'status' => 'integer',
            'approval_date' => 'datetime',
        ];
    }

    /**
     * Get the user who made the request.
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the admin who approved/rejected the request.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the province for the request.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the city for the request.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
