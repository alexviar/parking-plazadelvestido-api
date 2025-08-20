<?php

namespace App\Console\Commands;

use App\Models\CashClosing;
use App\Models\Ticket;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;

class DailyCashClosingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cash-closing:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        CashClosing::createDaily();
    }
}
