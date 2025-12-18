<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BloodInventory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'bag_id',
        'blood_donation_record_id',
        'province_id',
        'blood_type',
        'rh_factor',
        'entry_date',
        'exit_date',
        'expiration_date',
        'status',
        'added_by',
        'removed_by',
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
            'entry_date' => 'date',
            'exit_date' => 'date',
            'expiration_date' => 'date',
            'status' => 'integer',
        ];
    }

    /**
     * Get the blood donation record for this inventory entry.
     */
    public function bloodDonationRecord(): BelongsTo
    {
        return $this->belongsTo(BloodDonationRecord::class);
    }

    /**
     * Get the province for this inventory entry.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the user who added this inventory entry.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get the user who removed this inventory entry.
     */
    public function removedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'removed_by');
    }
}
