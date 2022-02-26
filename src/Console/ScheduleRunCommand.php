<?php

namespace Adiafora\Schedule\Console;

use Illuminate\Console\Scheduling\ScheduleRunCommand as ParentScheduleRunCommand;
use Illuminate\Console\Events\ScheduledTaskSkipped;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Events\Dispatcher;

class ScheduleRunCommand extends ParentScheduleRunCommand
{
    protected $signature = 'cron-schedule:run {--cron=default}';

    /**
     * Execute the console command.
     *
     * @param Schedule         $schedule
     * @param Dispatcher       $dispatcher
     * @param ExceptionHandler $handler
     * @return void
     */
    public function handle(Schedule $schedule, Dispatcher $dispatcher, ExceptionHandler $handler)
    {
        $this->schedule = $schedule;
        $this->dispatcher = $dispatcher;
        $this->handler = $handler;

        foreach ($this->schedule->dueEvents($this->laravel, $this->option('cron')) as $event) {
            if (! $event->filtersPass($this->laravel)) {
                $this->dispatcher->dispatch(new ScheduledTaskSkipped($event));

                continue;
            }

            if ($event->onOneServer) {
                $this->runSingleServerEvent($event);
            } else {
                $this->runEvent($event);
            }

            $this->eventsRan = true;
        }

        if (! $this->eventsRan) {
            $this->info('No scheduled commands are ready to run.');
        }
    }
}