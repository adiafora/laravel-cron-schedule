<?php

namespace Adiafora\Schedule;

use Adiafora\Console\Schedule\ScheduleRunCommand;
use Adiafora\Console\Schedule\ScheduleWorkCommand;
use Illuminate\Support\ServiceProvider;

class CronScheduleDataProvider extends ServiceProvider
{
    protected $commands = [
        ScheduleRunCommand::class,
        ScheduleWorkCommand::class,
    ];

    public function register()
    {
        $this->commands($this->commands);
    }
}