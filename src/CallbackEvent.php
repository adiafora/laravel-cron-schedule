<?php

namespace Adiafora\Schedule;

use Illuminate\Console\Scheduling\CallbackEvent as ParentCallbackEvent;

class CallbackEvent extends ParentCallbackEvent
{
    public string $cron = 'default';

    public function onCron(string $cron): static
    {
        $this->cron = $cron;
        return $this;
    }
}
