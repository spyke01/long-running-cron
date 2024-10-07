<?php
namespace LongRunningCronJobs\Interfaces;

interface CronJobInterface {
	public function execute(): void;
}
