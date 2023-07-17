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
        'reason'
    ];

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

}
