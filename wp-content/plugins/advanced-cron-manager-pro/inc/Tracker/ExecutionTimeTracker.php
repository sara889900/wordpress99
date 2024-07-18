<?php
/**
 * ExecutionTimeTracker class
 */

namespace underDEV\AdvancedCronManagerPRO\Tracker;

use underDEV\AdvancedCronManagerPRO\Abstracts\Tracker as AbstractTracker;
use underDEV\AdvancedCronManagerPRO\Contract\CanTrackSingleValue;

/**
 * ExecutionTimeTracker class
 */
class ExecutionTimeTracker extends AbstractTracker implements CanTrackSingleValue {

	/**
	 * Tracker type
	 *
	 * @var string
	 */
	protected static $type = 'performance';

	/**
	 * Starting time
	 *
	 * @var float
	 */
	protected $starting_at;

	/**
	 * Execution time
	 *
	 * @var float
	 */
	protected $execution_time;
	
	/**
	 * Starts the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	public function track( $event ) {
		$this->starting_at = microtime( true );
	}

	/**
	 * Resolves the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	public function resolve( $event ) {
		$this->execution_time = microtime( true ) - $this->starting_at;
	}

	/**
	 * Formats the tracker as a string
	 *
	 * @since  2.5.0
	 * @return string
	 */
	public function __toString() {
		return sprintf( __( 'Execution time: %s ms', 'advanced-cron-manager' ), $this->execution_time * 1000 );
	}

}
