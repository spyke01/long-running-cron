<?php

namespace LongRunningCronJobs\CronJobs;

use LongRunningCronJobs\Interfaces\CronJobInterface;
use Exception;
use Monolog\Logger;

class LongRunningCronJob implements CronJobInterface {
	private CronJobInterface $cronJob;
	private Logger $logger;
	protected array $errors = [];
	private int $delay = 0;
	private ?int $maxRunTime = 1;

	public function __construct( CronJobInterface $cronJob, Logger $logger ) {
		$this->cronJob = $cronJob;
		$this->logger  = $logger;
	}

	/**
	 * Set the maximum number of minutes our cron job will run.
	 *
	 * @param int $minutesToRun
	 * @param int $secondsInBetween
	 *
	 * @return $this
	 */
	public function setMaxRunTime( int $minutesToRun, int $secondsInBetween ): LongRunningCronJob {
		$this->delay      = $secondsInBetween;
		$this->maxRunTime = $minutesToRun;

		return $this;
	}

	/**
	 * Run the cronJob for the maxRunTime.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function execute(): void {
		$loop_expiry_time = time() + $this->maxRunTime * 60;

		$this->logger->info( "Starting long running " . get_class( $this->cronJob ) . " cron job for {$this->maxRunTime} minutes" );

		while ( time() < $loop_expiry_time ) {
			$this->cronJob->execute();

			if ( $this->delay > 0 ) {
				$this->logger->info( "Sleeping for {$this->delay} seconds" );
				sleep( $this->delay );
			}
		}

		$this->logger->info( "Finished long running cron job" );
	}
}
