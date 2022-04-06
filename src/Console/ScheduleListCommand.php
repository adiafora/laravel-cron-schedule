<?php

namespace Adiafora\Schedule\Console;

use Adiafora\Schedule\Schedule as MySchedule;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleListCommand extends \Illuminate\Console\Scheduling\ScheduleListCommand
{
    /**
     * @var string
     */
    protected $signature = 'cron-schedule:list {--timezone= : The timezone that times should be displayed in}';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        protected MySchedule $mySchedule,
    ) {
        parent::__construct();
    }

    public function handle(Schedule $schedule)
    {
        parent::handle($this->mySchedule);
    }
}