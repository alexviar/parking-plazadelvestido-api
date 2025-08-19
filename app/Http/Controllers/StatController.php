<?php

namespace App\Http\Controllers;

use App\Models\Stat;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class StatController extends Controller
{
    public function getDailyStats(Request $request)
    {
        $date = $request->input('date') ? Date::parse($request->input('date'))->startOfDay() : Date::today();
        $count = Ticket::whereDate('exit_time', $date)->count();
        $amount = Ticket::whereDate('exit_time', $date)->sum('amount');

        return response()->json([
            'date' => $date,
            'total_tickets' => $count,
            'total_amount' => $amount,
        ]);
    }
}
