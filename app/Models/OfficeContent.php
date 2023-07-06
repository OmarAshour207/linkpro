<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'note',
        'user_id',
        'floor_id',
        'path_id',
        'office_id'
    ];

    // Scopes
    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search){
            $q->where('content', 'like', "%$search%");
        });
    }

    public function scopeWhenOffice($query, $office)
    {
        return $query->when($office, function ($q) use ($office){
            $q->where('office_id', "$office");
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

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }
}
