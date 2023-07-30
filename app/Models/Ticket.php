<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'floor_id',
        'path_id',
        'office_id',

        'user_id',
        'service_id',
        'date',
        'start_time',
        'end_time',
        'notes',
        'type',
        'status',
        'reason',
        'prepare_time',
        'status_updated_at'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
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
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function ticketData()
    {
        return $this->hasMany(TicketData::class, 'ticket_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'ticket_id');
    }

    // Scopes

    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            return $q->whereHas('company', function ($qu) use ($search) {
                return $qu->where('name', 'like', "%$search%")
                    ->orWhere('phonenumber', 'like', "%$search%");
            });
        });
    }
    public function scopeWhenFrom($query, $from)
    {
        return $query->when($from, function ($q) use ($from) {
            return $q->where('created_at', '>=', $from . ' 00:00:00');
        });
    }
    public function scopeWhenTo($query, $to)
    {
        return $query->when($to, function ($q) use ($to) {
            return $q->where('created_at', '<=', $to . ' 00:00:00');
        });
    }
    public function scopeWhenStatus($query, $status)
    {
        return $query->when($status, function ($q) use ($status) {
            if ($status == 'all')
                return $q;
            return $q->where('status', $status);
        });
    }
}
