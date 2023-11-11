<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Apartment extends Model
{
    use HasFactory;

    protected $table = 'apartments';

    protected $guarded = [
        'approve_status'
    ];

    public function apartmentType(): BelongsTo
    {
        return $this->belongsTo(ApartmentType::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'owner_email', 'owner_email');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(RequestMail::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
