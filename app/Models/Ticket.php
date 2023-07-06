<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_from',
        'date_to',
        'time_from',
        'status',
        'reason',
        'content',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function ticketData()
    {
        return $this->hasMany(TicketData::class, 'ticket_id');
    }
}
