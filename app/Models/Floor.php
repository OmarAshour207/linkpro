<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id'
    ];

    // Scopes
    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where('title', 'like', "%$search%");
        });
    }

    // Relations
    public function company()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
