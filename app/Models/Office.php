<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'floor_id',
        'path_id'
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

    public function path()
    {
        return $this->belongsTo(Path::class, 'path_id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'office_id');
    }
}
