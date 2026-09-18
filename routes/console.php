<?php

use Illuminate\Support\Facades\Schedule;

// Nettoyage des sessions et tokens expirés — toutes les heures
Schedule::command('auth:prune-expired-tokens')->hourly();
