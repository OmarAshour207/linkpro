<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type'
    ];

    public function getMediaImageAttribute()
    {
        return url('/uploads/temp/users/' . $this->type . '/' . $this->name);
//        return Storage::url($this->type . '/thumb_' . $this->name);
    }

    public function getTempMediaImageAttribute()
    {
        return url('/uploads/temp/users/' . $this->type . '/thumb_' . $this->name);
//        return Storage::url('temp/' .$this->type . '/thumb_' . $this->name);
    }
}
