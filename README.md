Laravel Cron Scheduling
=====================

[Laravel Task Scheduling](https://laravel.com/docs/9.x/scheduling) is a great way to manage the cron. But the documentation contains the following warning:

> By default, multiple tasks scheduled at the same time will execute sequentially based on the order they are defined in your schedule method. If you have long-running tasks, this may cause subsequent tasks to start much later than anticipated.

The ```runInBackground()``` method can help you. But!

> The runInBackground method may only be used when scheduling tasks via the command and exec methods.

What should I do if I use code instead of running commands?..

Using our package, you can run as many cron as you want on the server, specifying its name for each one. And in the ```App\Console\Kernel``` you can specify which cron should handle this task in the ```onCron()``` method. A сron launched without specifying a name will be considered a default cron. You don't need to specify anything to put a task in this cron.

Install
-----------------------------------

Run:
```php
    composer require adiafora/laravel-cron-schedule
```

Usage
-----------------------------------

### App\Console\Kernel

In the ```App\Console\Kernel```, replace the line:

```php
    use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
```

with this:

```php
    use Adiafora\Schedule\Kernel as ConsoleKernel;
```

### Server

Now delete the cron entry on your server:

```
 * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

And add an entry instead:
```
 * * * * * cd /path-to-your-project && php artisan cron-schedule:run >> /dev/null 2>&1
```

Your scheduler is working now as before.

### Another cron

You can add another cron to your server by specifying its name using the option ```--cron=```. Let's give it a name ```longTime```:

```
 * * * * * cd /path-to-your-project && php artisan cron-schedule:run --cron=longTime >> /dev/null 2>&1
```

And now you can specify the name of this cron for any task in the scheduler.

```php
    $schedule->call(function () {
        $this->mailService->send();
        sleep(60);
        $this->mailService->send(true);
    })
        ->onCron('longTime')
        ->dailyAt('03:00');

    $schedule->call(function () {
        $this->report->prepare();
    })
        ->dailyAt('03:00');
```

Note that the task of preparing the report will work exactly at 03:00, without waiting for the emails sending! This way you can create as many crones as you want, give them names and assign tasks to them, distributing them as you see fit. And in each cron, the task will be launched strictly at the specified time, without waiting for other tasks launched at the same time in other crones.

License
-----------------------------------

The MIT License (MIT). Please see License File for more information.