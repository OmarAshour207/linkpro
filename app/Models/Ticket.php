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
        'content_id',
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

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
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
