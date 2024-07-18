<?php
/**
 * Event Capture class
 */

namespace underDEV\AdvancedCronManagerPRO;

use underDEV\AdvancedCronManager\Cron\Element\Event;

class EventCapture {

	/**
	 * Event instance.
	 *
	 * @var Event
	 */
	private $event;

	/**
	 * Event trackers.
	 *
	 * This is just an array with tracker slugs.
	 *
	 * @var array
	 */
	private $trackers = array();

	/**
	 * Was the event captured?
	 *
	 * @var bool
	 */
	private $captured = false;

	/**
	 * Class constructor
	 *
	 * @param Event $event    ACM Cron event instance.
	 * @param array $trackers Trackers to use.
	 */
	public function __construct( Event $event, $trackers = array() ) {
		$this->event    = $event;
		$this->trackers = $trackers;
	}

	/**
	 * Registers event listeners
	 *
	 * @since  2.5.0
	 * @return $this
	 */
	public function register_listeners() {
		add_action( $this->event->hook, array( $this, 'pre_execution' ), -99999999999999999, 0 );
		add_action( $this->event->hook, array( $this, 'ensure_trackers_finished' ), 99999999999999999, 0 );

		return $this;
	}

	/**
	 * Listens for event pre execution.
	 *
	 * @since  2.5.0
	 * @return void 
	 */
	public function pre_execution() {
		$this->captured = true;

		foreach ( $this->trackers as $tracker ) {
			$tracker->track( $this->event );
		}
	}

	/**
	 * Ensures the trackers finished their job.
	 *
	 * @since  2.5.0
	 * @return void 
	 */
	public function ensure_trackers_finished() {
		foreach ( $this->trackers as $tracker ) {
			$tracker->finish( $this->event );
		}
	}

	/**
	 * Gets captured event.
	 *
	 * @since  2.5.0
	 * @return Event 
	 */
	public function get_event() {
		return $this->event;
	}

	/**
	 * Gets saved trackers data
	 *
	 * @since  2.5.0
	 * @return array 
	 */
	public function get_logs() {
		$logs = array();

		if ( ! $this->captured ) {
			return $logs;
		}

		foreach ( $this->trackers as $tracker ) {
			// Log the data only if the tracker caught something.
			if ( $tracker->is_dirty() ) {
				// Even if the tracker is returning a single value,
				// cast that to an array to avoid additional checks.
				foreach ( (array) $tracker->get_data() as $data ) {
					$logs[] = [ $tracker->get_type() => $data ];
				}
			}
		}

		return $logs;
	}

	/**
	 * Checks if event was captured
	 *
	 * @since  2.5.0
	 * @return bool 
	 */
	public function is_captured() {
		return $this->captured;
	}

}
