<?php

namespace Example\CronJobs;

use Example\Commands\EventCommands;
use Example\Constants\EventConstants;
use LongRunningCronJobs\Interfaces\CronJobInterface;
use Exception;
use Monolog\Logger;

class ProcessSendNotificationEventsCronJob implements CronJobInterface {
	private EventCommands $EventCommands;
	private Logger $logger;

	protected array $errors = [];
	private ?int $limit = null;

	public function __construct( Logger $logger ) {
		$this->logger         = $logger;
		$this->EventCommands  = new EventCommands( EventConstants::SEND_ORDER_NOTIFICATION );
	}

	/**
	 * Set the limit for the number of records our cron job processes in each run.
	 *
	 * @param int|null $limit
	 *
	 * @return ProcessSendNotificationEventsCronJob
	 */
	public function setLimit( ?int $limit ): ProcessSendNotificationEventsCronJob {
		$this->limit = $limit;

		return $this;
	}

	/**
	 * Process order events.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function execute(): void {
		$orderEvents = $this->EventCommands->getEvents( $this->limit );

		if ( empty( $orderEvents ) ) {
			$this->logger->debug( "No send notification events to process" );

			return;
		}

		$this->logger->info( "Starting to process send notification events" );
		$this->processOrderEvents( $orderEvents );
		$this->logger->info( "Finished processing send notification events" );
	}

	/**
	 * Process the order events.
	 *
	 * @param array $orderEvents
	 *
	 * @return void
	 * @throws Exception
	 */
	protected function processOrderEvents( array $orderEvents ): void {
		$eventIDs = ( array_values( array_column( $orderEvents, 'uuid' ) ) );
		$this->EventCommands->markAsProcessing( $eventIDs );

		foreach ( $orderEvents as $event ) {
			$eventID = $event['uuid'];

			try {
				$this->processOrderEvent( $event );
				$this->EventCommands->markAsProcessed( $eventID );
				$this->logger->info( "Processed event id $eventID" );
			} catch ( Exception $e ) {
				$this->EventCommands->markAsFailed( $eventID, $e->getMessage() );
				$this->logger->error( "Processed event id $eventID", [ 'error' => $e->getMessage() ] );
			}
		}
	}

	/**
	 * Process a single order event.
	 *
	 * @param array $event
	 *
	 * @return void
	 * @throws Exception
	 */
	protected function processOrderEvent( array $event ): void {
		$body    = json_decode( $event['body'], true );
		$orderID = $body['order_id'];

		if ( empty( $orderID ) ) {
			throw new Exception( 'Order ID is missing.' );
		}

		// Send the notifications
		$this->sendNotification( $orderID );
	}

	/**
	 * @param int|string $orderID
	 *
	 * @return void
	 * @throws Exception
	 */
	protected function sendNotification( int|string $orderID ): void {
		// Email the store about this order
		try {
			throw new Exception('This is where you would use the $orderID array to process the SEND_NOTIFICATION_EVENT');
		} catch ( Exception $e ) {
			$this->logger->debug( 'Email to the store could not be sent', [ 'error' => $e->getMessage() ] );
		}
	}
}
