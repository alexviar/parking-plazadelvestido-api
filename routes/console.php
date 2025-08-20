<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('cash-closing:daily')->daily();
