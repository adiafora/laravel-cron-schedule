<?php

namespace App\Libraries\Schedule;

use Illuminate\Console\Scheduling\Schedule as ParentSchedule;

class Schedule extends ParentSchedule
{
    public function call($callback, array $parameters = [])
    {
        $this->events[] = $event = new CallbackEvent(
            $this->eventMutex, $callback, $parameters, $this->timezone
        );

        return $event;
    }
}
