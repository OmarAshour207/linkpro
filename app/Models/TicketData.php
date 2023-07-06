<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketData extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'supply_id',
        'quantity',
        'unit',
        'officecontent_id',
        'notes'
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class, 'supply_id');
    }

    public function officeContent()
    {
        return $this->belongsTo(OfficeContent::class, 'officecontent_id');
    }
}
