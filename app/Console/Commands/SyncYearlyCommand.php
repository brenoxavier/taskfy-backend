<?php

/** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Services\ClockifyService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

class SyncYearlyCommand extends Command
{
    protected $signature = 'sync:yearly';

    protected $description = 'Syncs Clockify time entries over a period of one year';

    public function handle(): int
    {
        try {
            ClockifyService::sincronizarEntradasClockify(Carbon::today()->startOfYear());
            return 1;
        } catch (Exception $exception) {
            dd($exception);
            return 0;
        }
    }
}
