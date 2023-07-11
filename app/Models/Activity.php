<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'responsible_id',
        'type',
        'comment'
    ];

    // relations

    public function user()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
