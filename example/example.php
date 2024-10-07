<?php
// For simplicity, we are referencing the libraries autoload. In your project just use composer install spyke01/longrunningcronjobs
require dirname( __DIR__, 1 ) . '/vendor/autoload.php';

require 'vendor/autoload.php';

use LongRunningCronJobs\CronJobs\LongRunningCronJob;
use Example\CronJobs\ProcessSendNotificationEventsCronJob;
// use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger( 'processSendNotificationEvents' );
$logger->pushHandler( new StreamHandler( 'php://stdout', Level::Debug ) );
// $logger->pushHandler( new RotatingFileHandler( dirname( __DIR__, 1 ) . "/logs/processSendNotificationEvents.log", 30, Level::Debug ) );

try {
	$ProcessSendNotificationEventsCronJob = new ProcessSendNotificationEventsCronJob( $logger );
	$ProcessSendNotificationEventsCronJob->setLimit( 3 );

	$LongRunningCronJob = new LongRunningCronJob( $ProcessSendNotificationEventsCronJob, $logger );
	$LongRunningCronJob->setMaxRunTime( 1, 5 )->execute();
} catch ( Exception $e ) {
	$logger->error( "Cron Job Failed: " . $e->getMessage() );
}

$logger->info( "Cron Job Completed" );
