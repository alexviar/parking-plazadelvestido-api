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
        $date = today();
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
            $totalTickets,
            $totalAmount,
            $minCode,
            $maxCode,
            $lastScannedCode
        ]);
        if ($totalTickets > 0) {
            $gaps = collect([[
                'from' => (int) Str::substr($minCode, -4),
                'to' => (int) Str::substr($maxCode, -4),
            ]]);
            (clone $ticketsQuery)
                ->orderBy("code")
                ->chunk(100, function ($chunks) use (&$gaps) {
                    $lastIncludedCode = '';
                    foreach ($chunks as $ticket) {
                        if ($ticket->code == $lastIncludedCode) {
                            continue;
                        }
                        $lastIncludedCode = $ticket->code;
                        $folio = $ticket->folio;
                        logger($ticket->id, [$ticket->code]);

                        $mod = 10000;
                        $gap = $gaps->pop();

                        if ($gap['from'] != $folio) {
                            $gaps->push([
                                'from' => $gap['from'],
                                'to' => (($folio - 1) % $mod + $mod) % $mod
                            ]);
                        }
                        if ($gap['to'] != $folio) {
                            $gaps->push([
                                'from' => ($folio + 1) % $mod,
                                'to' => $gap['to']
                            ]);
                        }
                    }
                });

            if ($lastScannedCode) {
                $previousFolio = (int) Str::substr($lastScannedCode, -4);
                $expectedFolio = ($previousFolio + 1) % 10000;
                $currentFolio = (int) Str::substr($minCode, -4);
                if ($expectedFolio != $minCode) {
                    $gaps = $gaps->splice(0, 0, [
                        'from' => $expectedFolio,
                        'to' => $currentFolio
                    ]);
                }
            }
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
