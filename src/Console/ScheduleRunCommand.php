<?php

namespace Adiafora\Schedule\Console;

use Adiafora\Schedule\Schedule as MySchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\ScheduleRunCommand as ParentScheduleRunCommand;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Events\Dispatcher;

class ScheduleRunCommand extends ParentScheduleRunCommand
{
    /**
     * @var string
     */
    protected $signature = 'cron-schedule:run {--cron=default}';

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

    /**
     * Execute the console command.
     *
     * @param Schedule         $schedule
     * @param Dispatcher       $dispatcher
     * @param ExceptionHandler $handler
     *
     * @return void
     */
    public function handle(Schedule $schedule, Dispatcher $dispatcher, ExceptionHandler $handler)
    {
        $this->mySchedule->setCron($this->option('cron'));
        parent::handle($this->mySchedule, $dispatcher, $handler);
    }
}