<?php
/**
 * Logger class
 */

namespace underDEV\AdvancedCronManagerPRO;

use underDEV\AdvancedCronManager\Cron\Events;

class Logger {

	/**
	 * Events class
	 * @var object
	 */
	private $events;

	/**
	 * LogsLibrary class
	 * @var object
	 */
	private $library;

	/**
	 * License Manager class
	 * @var object
	 */
	private $license_manager;

	/**
	 * LogOptions class
	 * @var object
	 */
	private $options;

	/**
	 * Captures collection
	 * @var array
	 */
	private $captures = array();

	/**
	 * Time offser
	 * @var string
	 */
	private $time_offset;

	/**
	 * Date format
	 * @var string
	 */
	private $date_format;

	/**
	 * Time format
	 * @var string
	 */
	private $time_format;

	/**
	 * Datetime format
	 * @var string
	 */
	private $datetime_format;

	/**
	 * Class constructor
	 */
	public function __construct( Events $events, LogsLibrary $library, License\Manager $license_manager, LogOptions $options ) {

		$this->events          = $events;
		$this->library         = $library;
		$this->license_manager = $license_manager;
		$this->options         = $options;

		$this->time_offset     = get_option( 'gmt_offset' ) * 3600;
		$this->date_format     = get_option( 'date_format' );
		$this->time_format     = get_option( 'time_format' );
		$this->datetime_format = $this->date_format . ' ' . $this->time_format;

	}

	/**
	 * Adds cron actions to observe them
	 * @return void
	 */
	public function add_actions() {

		if ( ! $this->options->is_active( 'log_executions' ) ) {
			return;
		}

		// This is a tracker with global state. It cannot track
		// events independently, so we are using globalized instance.
		$error_tracker = new Tracker\ErrorTracker();

		foreach ( $this->events->get_events( true ) as $event_hash => $event ) {

			$trackers = array();

			if ( $this->options->is_active( 'log_warnings' ) ) {
				$trackers[] = new Tracker\WarningTracker();
			}

			if ( $this->options->is_active( 'log_errors' ) ) {
				$trackers[] = $error_tracker;
			}

			if ( $this->options->is_active( 'log_performance' ) ) {
				$trackers[] = new Tracker\ExecutionTimeTracker();
				$trackers[] = new Tracker\MemoryUsageTracker();
			}

			$trackers[] = new Tracker\LogTracker();

			$trackers = apply_filters( 'advanced-cron-manager/trackers', $trackers, $event );

			if ( empty( $trackers ) ) {
				continue;
			}

			$this->captures[] = ( new EventCapture( $event, $trackers ) )->register_listeners();

		}

	}

	/**
	 * Handles the script end
	 *
	 * @since  2.5.0
	 * @return void
	 */
	public function shutdown() {

		// Ensure all the captured events and their trackers finished the job.
		foreach ( $this->captures as $capture ) {
			$capture->ensure_trackers_finished();
		}

		$this->save_logs();
		
	}

	/**
	 * Saves the logs
	 *
	 * @since  2.5.0
	 * @return void
	 */
	public function save_logs() {

		if ( ! $this->license_manager->is_license_valid() ) {
			return;
		}

		// Save logs in the database.
		foreach ( $this->captures as $capture ) {
			if ( $capture->is_captured() ) {
				$this->library->save_log( $capture->get_event(), $capture->get_logs() );
			}
		}
		
	}

}
