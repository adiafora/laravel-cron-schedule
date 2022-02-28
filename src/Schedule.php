<?php

namespace Adiafora\Schedule;

use Closure;
use Illuminate\Console\Scheduling\Schedule as ParentSchedule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;

class Schedule extends ParentSchedule
{
    /**
     * Only tasks of this cron will be started.
     *
     * @var string
     */
    protected string $cron;

    /**
     * Set cron name.
     *
     * @param $cron
     */
    public function setCron($cron)
    {
        $this->cron = $cron;
    }

    /**
     * Add a new callback event to the schedule.
     *
     * @param string|callable $callback
     * @param array           $parameters
     *
     * @return \Illuminate\Console\Scheduling\CallbackEvent
     */
    public function call($callback, array $parameters = [])
    {
        $this->events[] = $event = new CallbackEvent(
            $this->eventMutex, $callback, $parameters, $this->timezone
        );

        return $event;
    }

    /**
     * Get all of the events on the schedule that are due.
     *
     * @param Application $app
     *
     * @return Collection
     */
    public function dueEvents($app)
    {
        return collect($this->events)
            ->filter($this->cronFilter())
            ->filter
            ->isDue($app);
    }

    /**
     * Leave only tasks for the current cron.
     *
     * @return Closure
     */
    protected function cronFilter(): Closure
    {
        return function ($value) {
            return $value->cron === $this->cron;
        };
    }
}
