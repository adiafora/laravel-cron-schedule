<?php

namespace Adiafora\Schedule;

use Closure;
use Illuminate\Console\Scheduling\Schedule as ParentSchedule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;

class Schedule extends ParentSchedule
{
    /**
     * Add a new callback event to the schedule.
     *
     * @param  string|callable  $callback
     * @param  array  $parameters
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
     * @param  Application $app
     * @param  string|null  $option
     *
     * @return Collection
     */
    public function dueEvents($app, ?string $option = null)
    {
        return collect($this->events)
            ->filter
            ->filter($this->cronFilter($option))
            ->isDue($app);
    }

    /**
     * Leave only tasks for the current cron.
     *
     * @param $option
     *
     * @return Closure
     */
    protected function cronFilter($option): Closure
    {
        return function ($value) use ($option) {
            return $value->cron == $option;
        };
    }
}
