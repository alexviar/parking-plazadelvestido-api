<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    /** @use HasFactory<\Database\Factories\TariffFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'threshold',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'threshold' => 'integer',
        ];
    }
}
