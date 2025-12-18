<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BloodTest extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'blood_donation_record_id',
        'hiv_result',
        'hbv_result',
        'hcv_result',
        'syphilis_result',
        'malaria_result',
        'overall_result',
        'test_date',
        'tested_by',
        'test_logs',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hiv_result' => 'boolean',
            'hbv_result' => 'boolean',
            'hcv_result' => 'boolean',
            'syphilis_result' => 'boolean',
            'malaria_result' => 'boolean',
            'overall_result' => 'boolean',
            'test_date' => 'date',
        ];
    }

    /**
     * Get the blood donation record for this test.
     */
    public function bloodDonationRecord(): BelongsTo
    {
        return $this->belongsTo(BloodDonationRecord::class);
    }

    /**
     * Get the user who performed the test.
     */
    public function tested_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tested_by');
    }
}
