<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'user_type',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'user_type' => 'integer',
        ];
    }

    /**
     * Get the donor profile associated with the user.
     */
    public function donor(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Donor::class);
    }

    /**
     * Get the hospital user profile associated with the user.
     */
    public function hospitalUser(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(HospitalUser::class);
    }

    /**
     * Get the blood requests made by this user.
     */
    public function bloodRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BloodRequest::class, 'requested_by');
    }

    /**
     * Get the blood requests approved by this user.
     */
    public function approvedBloodRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BloodRequest::class, 'approved_by');
    }

    /**
     * Get the blood donation records recorded by this admin.
     */
    public function recordedBloodDonations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BloodDonationRecord::class, 'recorded_by_admin');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true || $this->is_admin === 1;
    }
}
