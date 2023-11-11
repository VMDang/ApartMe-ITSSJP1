<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $guarded = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table:  'room_user',
            foreignPivotKey: 'role_id',
            relatedPivotKey: 'user_id'
        );
    }
}
