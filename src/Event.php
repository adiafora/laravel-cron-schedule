<?php

namespace Adiafora\Schedule;

class Event extends \Illuminate\Console\Scheduling\Event
{
    /**
     * The name of the cron in which this task should be performed.
     *
     * @var string
     */
    public string $cron = 'default';

    /**
     * Set the desired cron for the job.
     *
     * @param string $cron
     *
     * @return $this
     */
    public function onCron(string $cron): static
    {
        $this->cron = $cron;
        return $this;
    }
}