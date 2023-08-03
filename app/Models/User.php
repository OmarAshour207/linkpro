<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phonenumber',
        'password',
        'role',
        'image',
        'address',
        'lat',
        'lng',
        'supervisor_id',
        'fcm_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Attributes
    public function getUserImageAttribute()
    {
        if ($this->image)
            return url('/uploads/users/' . $this->image);
        return url('/uploads/users/avatar.png');
//        return Storage::url('users/' . $this->image);
    }

    public function getThumbImageAttribute()
    {
        if ($this->image)
            return url('/uploads/users/thumb_' . $this->image);
        return url('/uploads/users/thumb_avatar.png');
//        return Storage::url('users/thumb_' . $this->image);
    }

    // scopes

    public function scopeCompanies($query)
    {
        return $query->where('role', 'company');
    }

    public function scopeSupervisors($query)
    {
        return $query->where('role', 'supervisor');
    }

    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            return $q->where('email', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('phonenumber', 'like', "%$search%");
        });
    }
    public function scopeWhenRole($query, $role)
    {
        return $query->when($role, function ($q) use ($role) {
            return $q->where('role', $role);

        });
    }

    public function floors()
    {
        return $this->hasMany(Floor::class, 'user_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id')->withDefault();
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function supplies()
    {
        return $this->hasMany(Supply::class, 'user_id');
    }
}
