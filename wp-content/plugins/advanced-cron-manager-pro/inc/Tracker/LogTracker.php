<?php
/**
 * LogTracker class
 */

namespace underDEV\AdvancedCronManagerPRO\Tracker;

use underDEV\AdvancedCronManagerPRO\Abstracts\Tracker as AbstractTracker;
use underDEV\AdvancedCronManagerPRO\Contract\CanTrackMultiValue;

/**
 * LogTracker class
 */
class LogTracker extends AbstractTracker implements CanTrackMultiValue {

	/**
	 * Tracker type
	 *
	 * @var string
	 */
	protected static $type = 'custom';

	/**
	 * Logs.
	 *
	 * @var array
	 */
	protected $logs = array();

	/**
	 * Starts the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	public function track( $event ) {
		// Nothing to be initialized.
	}

	/**
	 * Resolves the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	public function resolve( $event ) {
		do_action( 'advanced-cron-manager/log', $this );
	}

	/**
	 * Saves the log.
	 * 
	 * @param  mixed $thing Anything which is worth saving,
	 *                      will be serialized if needed.
	 * @return void
	 */
	public function log( $thing ) {
		$this->logs[] = maybe_serialize( $thing );
	}

	/**
	 * Formats the tracker as an array
	 *
	 * @since  2.5.0
	 * @return array
	 */
	public function to_array() {
		return $this->logs;
	}
	
}
