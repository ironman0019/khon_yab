<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the cities for the province.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get the donors in this province.
     */
    public function donors(): HasMany
    {
        return $this->hasMany(Donor::class);
    }

    /**
     * Get the blood requests in this province.
     */
    public function bloodRequests(): HasMany
    {
        return $this->hasMany(BloodRequest::class);
    }

    /**
     * Get the blood inventory in this province.
     */
    public function bloodInventory(): HasMany
    {
        return $this->hasMany(BloodInventory::class);
    }
}
