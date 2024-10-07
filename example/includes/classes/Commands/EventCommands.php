<?php

namespace Example\Commands;

use Example\Constants\EventConstants;
use Example\Constants\EventStatusConstants;
use Exception;

class EventCommands {
	private string $eventToProcess;
	// private Event $Event;

	/**
	 * @param string $event
	 */
	public function __construct( string $event ) {
		$this->eventToProcess = $event;

		// Set up an instance of your DB model
		// $this->Event = new Event( getDBInstance(), false );
	}

	/**
	 * Get pending events in FIFO order.
	 *
	 * @param int $limit
	 *
	 * @return mixed|null
	 * @throws Exception
	 */
	public function getEvents( int $limit = 0 ): mixed {
		// Get the events you want to handle for your cron job run
		// return $this->Event->findPendingEvents( $this->eventToProcess, $limit )->get();

		// For example purposes we just return a series of events
		return [
			[
				'uuid'    => 'a3af8fe7-011a-449a-bb33-83fedeede8c7',
				'event'   => EventConstants::SEND_ORDER_NOTIFICATION,
				'message' => '',
				'body'    => json_encode( [ 'order_id' => 1 ] ),
				'status'  => EventStatusConstants::PENDING
			],
			[
				'uuid'    => '29e561ac-b3ad-4d6a-bb2e-e6f9034813cd',
				'event'   => EventConstants::SEND_ORDER_NOTIFICATION,
				'message' => '',
				'body'    => json_encode( [ 'order_id' => 2 ] ),
				'status'  => EventStatusConstants::PENDING
			],
			[
				'uuid'    => '46a3df37-39b3-4b00-94fd-b009afa09668',
				'event'   => EventConstants::SEND_ORDER_NOTIFICATION,
				'message' => '',
				'body'    => json_encode( [ 'order_id' => 3 ] ),
				'status'  => EventStatusConstants::PENDING
			],
		];
	}

	/**
	 * Mark that an event is locked for processing.
	 *
	 * @param array $eventIDs
	 *
	 * @return void
	 */
	public function markAsProcessing( array $eventIDs ): void {
		// Update the event in the DB to show that it is currently being processed by the cron job
		// $this->Event->updateWhere(
		// 	[ 'status' => EventStatusConstants::PROCESSING ],
		// 	[ 'uuid' => [ 'IN' => $eventIDs ] ]
		// );
	}

	/**
	 * Mark that an event was processed successfully.
	 *
	 * @param string $eventID
	 *
	 * @return void
	 */
	public function markAsProcessed( string $eventID ): void {
		// Update the event in the DB to show that it is complete
		// $this->Event->update( $eventID,[ 'status' => EventStatusConstants::PROCESSED ] );
	}

	/**
	 * Mark an event as failed with a message explaining why.
	 *
	 * @param string $eventID
	 * @param string $message
	 *
	 * @return void
	 */
	public function markAsFailed( string $eventID, string $message ): void {
		// Update the event in the DB so you cna trigger any error handling processes or alerts you need to
		// $this->Event->update(
		// 	$eventID,
		// 	[
		// 		'status'  => EventStatusConstants::FAILED_TO_PROCESS,
		// 		'message' => $message
		// 	]
		// );
	}

	/**
	 * Create a new event.
	 *
	 * @param string $event
	 * @param array  $body
	 *
	 * @return bool|int|string
	 */
	public static function create( string $event, array $body ): bool|int|string {
		// Insert your record into the DB so that the cron job can pick it up
		// $Event = new Event( getDBInstance(), false );
		//
		// return $Event->create( [
		// 	'uuid'    => Uuid::uuid4()->toString(),
		// 	'event'   => $event,
		// 	'message' => '',
		// 	'body'    => json_encode( $body ),
		// 	'status'  => EventStatusConstants::PENDING
		// ] );

		// For example purposes we just return that the event was created
		return true;
	}
}
