<?php
/**
 * ErrorTracker class
 */

namespace underDEV\AdvancedCronManagerPRO\Tracker;

use underDEV\AdvancedCronManagerPRO\Abstracts\Tracker as AbstractTracker;
use underDEV\AdvancedCronManagerPRO\Contract\CanTrackSingleValue;

/**
 * ErrorTracker class
 */
class ErrorTracker extends AbstractTracker implements CanTrackSingleValue {

	/**
	 * Tracker type
	 *
	 * @var string
	 */
	protected static $type = 'error';

	/**
	 * Error message.
	 *
	 * @var string
	 */
	protected $error = '';

	/**
	 * Starts the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	public function track( $event ) {
		// There's nothing to be set.
	}

	/**
	 * Resolves the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	public function resolve( $event ) {
		$error = error_get_last();

		// No error, just skip the error handling code.
		if ( null === $error ) {
			return;
		}

		$error_types_to_handle = array(
			E_ERROR,
			E_PARSE,
			E_USER_ERROR,
			E_COMPILE_ERROR,
			E_RECOVERABLE_ERROR,
		);

		if ( ! isset( $error['type'] ) || ! in_array( $error['type'], $error_types_to_handle, true ) ) {
			return;
		}

		$this->error = sprintf( 'Fatal error: %s in %s at line %s', $error['message'], $error['file'], $error['line'] );

		do_action( 'advanced-cron-manager/error', null, $event, $this->error, 'fatal' );
	}

	/**
	 * Formats the tracker as a string
	 *
	 * @since  2.5.0
	 * @return string
	 */
	public function __toString() {
		return $this->error;
	}
	
}
