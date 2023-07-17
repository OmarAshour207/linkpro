<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketData extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'content_id',

        'ticket_id',

        'supply_id',
        'quantity',
        'unit',

        'note'
    ];

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }
    public function supply()
    {
        return $this->belongsTo(Supply::class, 'supply_id');
    }

}
