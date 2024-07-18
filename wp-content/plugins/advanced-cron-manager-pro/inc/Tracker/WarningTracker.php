<?php
/**
 * WarningTracker class
 */

namespace underDEV\AdvancedCronManagerPRO\Tracker;

use underDEV\AdvancedCronManagerPRO\Abstracts\Tracker as AbstractTracker;
use underDEV\AdvancedCronManagerPRO\Contract\CanTrackMultiValue;

/**
 * WarningTracker class
 */
class WarningTracker extends AbstractTracker implements CanTrackMultiValue {

	/**
	 * Tracker type
	 *
	 * @var string
	 */
	protected static $type = 'warning';

	/**
	 * Error messages.
	 *
	 * @var array
	 */
	protected $errors = array();

	/**
	 * Starts the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	public function track( $event ) {
		set_error_handler( function( $code, $message, $file, $line ) {
			// Translators: 1. Error message, 2. File path, 3. File line.
			$this->errors[] = sprintf( '%s in %s at line %s', $message, $file, $line );
		} );
	}

	/**
	 * Resolves the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	public function resolve( $event ) {
		restore_error_handler();
	}

	/**
	 * Formats the tracker as an array
	 *
	 * @since  2.5.0
	 * @return array
	 */
	public function to_array() {
		return $this->errors;
	}
	
}
