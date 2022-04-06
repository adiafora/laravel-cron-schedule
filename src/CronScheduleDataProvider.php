<?php

namespace Adiafora\Schedule;

use Adiafora\Schedule\Console\ScheduleListCommand;
use Adiafora\Schedule\Console\ScheduleRunCommand;
use Adiafora\Schedule\Console\ScheduleWorkCommand;
use Illuminate\Support\ServiceProvider;

class CronScheduleDataProvider extends ServiceProvider
{
    protected $commands = [
        ScheduleRunCommand::class,
        ScheduleWorkCommand::class,
        ScheduleListCommand::class,
    ];

    public function register()
    {
        $this->commands($this->commands);
    }
}