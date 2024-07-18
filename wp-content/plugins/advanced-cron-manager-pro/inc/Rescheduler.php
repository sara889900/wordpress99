<?php
/**
 * Rescheduler class
 */

namespace underDEV\AdvancedCronManagerPRO;

use underDEV\Utils;
use underDEV\AdvancedCronManager\Cron\Events;
use underDEV\AdvancedCronManager\Cron;


class Rescheduler {

	/**
	 * View class
	 * @var object
	 */
	public $view;

	/**
	 * Ajax class
	 * @var object
	 */
	public $ajax;

	/**
	 * Events class
	 * @var object
	 */
	private $events;

    /**
     * Schedules class
     *
     * @var instance of underDEV\AdvancedCronManager\Cron\Schedules
     */
    public $schedules;

	/**
	 * Class constructor
	 */
	public function __construct( Utils\View $view, Utils\Ajax $ajax, Events $events, Cron\Schedules $schedules ) {

		$this->view      = $view;
		$this->ajax      = $ajax;
		$this->events    = $events;
        $this->schedules = $schedules;
	}

	/**
	 * Displays reschedule row action
	 * @param  object $event Event object
	 * @return void
	 */
	public function display_row_action( $event ) {

		$this->view->set_var( 'event', $event, true );
		$this->view->get_view( 'row-action-reschedule' );

	}

	/**
	 * Reschedule event form
	 */
	public function reschedule_event_form() {

		$event = $this->events->get_event_by_hash( $_REQUEST['event_hash'] );

		$this->ajax->verify_nonce( 'acm/event/reschedule/' . $event->hash );

		if ( ! current_user_can( 'manage_options' ) ) {
			$this->ajax->response( false, array(
				__( "You're not allowed to do that.", 'advanced-cron-manager' ),
			) );
		}

		ob_start();

        $this->view->set_var( 'event', $event, true );
        $this->view->set_var( 'schedules', $this->schedules);
        $this->view->set_var( 'current_schedule', $this->schedules->get_schedule( $event->schedule ) );
        $this->view->get_view( 'reschedule-form' );

		$this->ajax->response( ob_get_clean() );

	}

	/**
	 * Reschedule event
	 * @return void
	 */
	public function reschedule() {

		$data  = wp_parse_args( $_REQUEST['data'], array() );
		$event = $this->events->get_event_by_hash( $data['event_hash'] );

		$this->ajax->verify_nonce( 'acm/event/reschedule/' . $event->hash );

		if ( ! current_user_can( 'manage_options' ) ) {
			$this->ajax->response( false, array(
				__( "You're not allowed to do that.", 'advanced-cron-manager' ),
			) );
		}

		$execution_timestamp = strtotime( $data['execution'] ) ? strtotime( $data['execution'] ) + ( HOUR_IN_SECONDS * $data['execution_offset'] ) : time() + ( HOUR_IN_SECONDS * $data['execution_offset'] );

		// Unschedule previous event.
		wp_unschedule_event( $event->next_call, $event->hook, $event->args );

		// Schedule next event.
		if ( 'single_event' === $event->schedule ) {
			wp_schedule_single_event( $execution_timestamp, $event->hook, $event->args );
		} else {
			wp_schedule_event( $execution_timestamp, $data[ 'schedule' ], $event->hook, $event->args );
		}

		$success = sprintf(
			esc_html__( 'Event "%s" has been rescheduled', 'advanced-cron-manager' ),
			$event->hook
		);

		$this->ajax->response( $success, array() );

	}

}
