<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApartmentType extends Model
{
    use HasFactory;

    protected $table = 'apartment_types';

    protected $guarded = [];

    public function apartments(): HasMany
    {
        return $this->hasMany(Apartment::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
