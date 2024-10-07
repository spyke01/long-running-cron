# Long Running Cron Jobs
CRON jobs are typically limited to execution every minute, which can create gaps between job completions and the start of the next job, especially for smaller processes. This library offers a solution for running long-duration jobs that can trigger multiple times within a minute, automatically terminating if the job exceeds one minute. Termination occurs after the current task is processed.

## Installation
To install the library, run:

```composer require spyke01/longrunningcronjobs```

### Options
The `setMaxRunTime` method enables you to customize both the execution duration of the cron job and the intervals between triggers.

* `$minutesToRun`: Specifies the maximum duration for the process to run. This value should align with your cron job settings.
* `$secondsInBetween`: Defines the number of seconds to pause between runs. Setting this to `0` eliminates wait time, but it may lead to issues if database queries are executed too quickly between processes.

## Examples
An example of an event-based cron job is available in the `example` folder.

## Alternatives

This library is best suited for scenarios where PHP is strictly running on a single server instance. If you are using a cloud provider like AWS, it is advisable to consider a queue-based solution, such as SQS, or an event-based approach using EventBridge.
