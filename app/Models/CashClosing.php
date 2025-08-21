<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CashClosing extends Model
{
    /** @use HasFactory<\Database\Factories\CashClosingFactory> */
    use HasFactory;

    protected $fillable = [
        'period_start',
        'period_end',
        'total_tickets',
        'total_amount',
        'gaps',
        'first',
        'last',
    ];

    public function casts()
    {
        return [
            'period_start' => 'datetime',
            'period_end' => 'datetime',
            'total_tickets' => 'integer',
            'total_amount' => 'float',
            'gaps' => 'array'
        ];
    }

    static function createDaily()
    {
        $date = now()->startOfMinute();
        $periodEnd = $date->copy();
        $periodStart = $date->copy()->subDay();

        $ticketConfig = TicketConfig::first();
        $lastScannedCode = $ticketConfig->last_scanned_code;

        $ticketsQuery = Ticket::query()
            ->where('exit_time', '>=', $periodStart)
            ->where('exit_time', '<',  $periodEnd);

        $totals = (clone $ticketsQuery)
            ->selectRaw('COUNT(*) as c, COALESCE(SUM(amount), 0) as s')
            ->first();
        $totalTickets = (int) ($totals->c ?? 0);
        $totalAmount  = (float) ($totals->s ?? 0.0);

        $minCode = (clone $ticketsQuery)->min('code');
        $maxCode = (clone $ticketsQuery)->max('code');


        logger('Hola', [
            $periodStart->toDateTimeLocalString(),
            $periodEnd->toDateTimeLocalString(),
            $totalTickets,
            $totalAmount,
            $minCode,
            $maxCode,
            $lastScannedCode
        ]);
        $previousFolio = (int) Str::substr($lastScannedCode ? $lastScannedCode : $minCode, -4);
        if (!$lastScannedCode) {
            $previousFolio -= 1;
        }

        if ($totalTickets > 0) {
            $gaps = [];
            $expectedFolio = $previousFolio;
            (clone $ticketsQuery)
                ->orderBy("code")
                ->chunk(100, function ($chunks) use (&$gaps, $expectedFolio) {
                    $lastIncludedCode = '';
                    foreach ($chunks as $ticket) {
                        if ($ticket->code == $lastIncludedCode) {
                            continue;
                        }
                        $lastIncludedCode = $ticket->code;
                        $folio = $ticket->folio;

                        $mod = 10000;
                        $expectedFolio = ($expectedFolio + 1) % $mod;
                        while ($expectedFolio != $folio) {
                            $gaps[] = $expectedFolio;
                            $expectedFolio = ($expectedFolio + 1) % $mod;
                        }
                    }
                });
        } else {
            $gaps = [];
            $minCode = null;
            $maxCode  = null;
        }

        $ticketConfig?->update([
            'last_scanned_code' => $maxCode
        ]);

        return static::create([
            'period_start'     => $periodStart,
            'period_end'       => $periodEnd,
            'total_tickets'    => $totalTickets,
            'total_amount'     => $totalAmount,
            'gaps'             => $gaps,
            'first'            => $minCode,
            'last'             => $maxCode,
        ]);
    }
}
