<?php

namespace Adiafora\Schedule\Console;

use Adiafora\Schedule\Schedule as MySchedule;
use Cron\CronExpression;
use DateTimeZone;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Carbon;

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
        foreach ($this->mySchedule->events() as $event) {
            $rows[] = [
                $event->command,
                $event->expression,
                $event->cron != 'default' ? $event->cron : '',
                $event->description,
                (new CronExpression($event->expression))
                    ->getNextRunDate(Carbon::now()->setTimezone($event->timezone))
                    ->setTimezone(new DateTimeZone($this->option('timezone') ?? config('app.timezone')))
                    ->format('Y-m-d H:i:s P'),
            ];
        }

        $this->table([
            'Command',
            'Interval',
            'Cron',
            'Description',
            'Next Due',
        ], $rows ?? []);
    }
}