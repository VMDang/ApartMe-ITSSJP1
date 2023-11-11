<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registrations';

    protected $guarded = [
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function apartment(): HasOne
    {
        return $this->hasOne(Apartment::class);
    }

    public function apartmentType(): BelongsTo
    {
        return $this->belongsTo(ApartmentType::class);
    }
}
