<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('auth:prune-expired-tokens')->hourly();
