<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'name',
        'quantity'
    ];

    // Scopes
    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search){
            $q->where('name', 'like', "%$search%");
        });
    }

    // Relations
    public function company()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
