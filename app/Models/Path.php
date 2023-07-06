<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'floor_id'
    ];

    // Scopes
    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search){
            $q->where('title', 'like', "%$search%");
        });
    }

    // Relations

    public function company()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }
}
