<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;

class Ticket extends Model
{
    protected $fillable = [
        'code',
        'entry_time',
        'exit_time',
        'duration',
        'amount',
        'folio',
    ];

    protected function casts(): array
    {
        return [
            'entry_time' => 'datetime',
            'exit_time' => 'datetime',
            'amount' => 'float',
        ];
    }
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;
}
