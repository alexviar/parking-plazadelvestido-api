<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketConfig extends Model
{
    /** @use HasFactory<\Database\Factories\TicketConfigFactory> */
    use HasFactory;

    protected $fillable = [
        'last_scanned_code'
    ];
}
