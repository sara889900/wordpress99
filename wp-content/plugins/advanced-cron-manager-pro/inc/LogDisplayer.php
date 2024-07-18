<?php
/**
 * LogDisplayer class
 */

namespace underDEV\AdvancedCronManagerPRO;
use underDEV\Utils;
use underDEV\AdvancedCronManager\Cron\Events;

class LogDisplayer {

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
	 * LogsLibrary class
	 * @var object
	 */
	private $logs_library;

	/**
	 * ListenersLibrary class
	 * @var object
	 */
	private $listeners_library;

	/**
	 * Events class
	 * @var object
	 */
	private $events;

	/**
	 * LogOptions class
	 * @var object
	 */
	private $options;

	/**
	 * Class constructor
	 */
	public function __construct(
        Utils\View $view,
        Utils\Ajax $ajax,
        LogsLibrary $logs_library,
        ListenersLibrary $listeners_library,
        Events $events,
        LogOptions $options
    ) {
		$this->view              = $view;
		$this->ajax              = $ajax;
        $this->logs_library      = $logs_library;
        $this->listeners_library = $listeners_library;
		$this->events            = $events;
		$this->options           = $options;

	}

	/**
	 * Displays tab
	 * @param  object $table_row_view View object
	 * @return void
	 */
	public function display_tab( $table_row_view ) {

		$event = $table_row_view->get_var( 'event' );

		$this->view->set_var( 'event_hash', $event->hash, true );
		$this->view->set_var( 'logs', $this->logs_library->get_event_logs( $event ), true );
		$this->view->set_var( 'total_logs', $this->logs_library->count_logs( $event ), true );
		$this->view->set_var( 'logs_library', $this->logs_library, true );

		$this->view->get_view( 'logs-table' );

	}

	/**
	 * Displays implementation info
	 * @param  object $table_row_view View object
	 * @return void
	 */
	public function display_implementation( $table_row_view ) {

		$this->view->get_view( 'implementation-info' );

	}

    /**
     * Displays implementation info
     * @param  object $table_row_view View object
     * @return void
     */
    public function display_listeners( $table_row_view ) {
        $event = $table_row_view->get_var( 'event' );

        $this->view->set_var('listeners', $this->listeners_library->get_listeners($event->hook), true);

        $this->view->get_view( 'listeners-display' );
    }

	/**
	 * Displays section
	 * @param  object $table_row_view View object
	 * @return void
	 */
	public function display_section( $table_row_view ) {

		if ( ! $this->options->is_active( 'display_section' ) ) {
			return;
		}

		$this->view->set_var( 'extended', true );
		$this->view->set_var( 'logs', $this->logs_library->get_logs() );
		$this->view->set_var( 'total_logs', $this->logs_library->count_logs() );
		$this->view->set_var( 'logs_library', $this->logs_library );

		$this->view->get_view( 'logs-section' );

	}

	/**
	 * Refreshed logs table for event
	 * Called via AJAX
	 * @return void
	 */
	public function refresh_logs() {

		if ( ! current_user_can( 'manage_options' ) ) {
			$this->ajax->response( false, array(
				__( "You're not allowed to do that.", 'advanced-cron-manager' ),
			) );
		}

		ob_start();

		if ( ! empty( $_REQUEST['event'] ) ) {

			$event = $this->events->get_event_by_hash( $_REQUEST['event'] );
			$this->view->set_var( 'logs', $this->logs_library->get_event_logs( $event ) );

		} else {

			$this->view->set_var( 'logs', $this->logs_library->get_logs() );
			$this->view->set_var( 'extended', true );

		}

		$this->view->set_var( 'logs_library', $this->logs_library );

		$this->view->get_view( 'logs-table' );

		$this->ajax->success( ob_get_clean() );

	}

	/**
	 * Loads more logs
	 * Called via AJAX
	 * @return void
	 */
	public function load_more() {

		if ( ! current_user_can( 'manage_options' ) ) {
			$this->ajax->response( false, array(
				__( "You're not allowed to do that.", 'advanced-cron-manager' ),
			) );
		}

		ob_start();

		if ( ! empty( $_REQUEST['event'] ) ) {

			$event = $this->events->get_event_by_hash( $_REQUEST['event'] );
			$this->view->set_var( 'logs', $this->logs_library->get_event_logs( $event, $_REQUEST['page'] ) );

		} else {

			$this->view->set_var( 'logs', $this->logs_library->get_logs( $_REQUEST['page'] ) );
			$this->view->set_var( 'extended', true );

		}

		$this->view->set_var( 'logs_library', $this->logs_library );

		$this->view->get_view( 'logs-rows' );

		$this->ajax->success( ob_get_clean() );

	}

}
