<?php

namespace Adiafora\Schedule\Console;

use Illuminate\Console\Scheduling\ScheduleWorkCommand as ParentScheduleWorkCommand;
use Illuminate\Support\Carbon;
use Symfony\Component\Process\Process;

class ScheduleWorkCommand extends ParentScheduleWorkCommand
{
    /**
     * @var string
     */
    protected $signature = 'cron-schedule:work {--cron=default}';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Schedule worker started successfully.');

        [$lastExecutionStartedAt, $keyOfLastExecutionWithOutput, $executions] = [null, null, []];

        while (true) {
            usleep(100 * 1000);

            if (Carbon::now()->second === 0 &&
                ! Carbon::now()->startOfMinute()->equalTo($lastExecutionStartedAt)) {
                $executions[] = $execution = new Process([PHP_BINARY, 'artisan', 'cron-schedule:run', $this->getCronName()]);

                $execution->start();

                $lastExecutionStartedAt = Carbon::now()->startOfMinute();
            }

            foreach ($executions as $key => $execution) {
                $output = trim($execution->getIncrementalOutput()).
                    trim($execution->getIncrementalErrorOutput());

                if (! empty($output)) {
                    if ($key !== $keyOfLastExecutionWithOutput) {
                        $this->info(PHP_EOL.'['.date('c').'] Execution #'.($key + 1).' output:');

                        $keyOfLastExecutionWithOutput = $key;
                    }

                    $this->output->writeln($output);
                }

                if (! $execution->isRunning()) {
                    unset($executions[$key]);
                }
            }
        }
    }

    /**
     * @return string
     */
    protected function getCronName(): string
    {
        return '--cron=' . $this->option('cron');
    }
}