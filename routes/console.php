<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('cash-closing:daily')->dailyAt('20:00');
