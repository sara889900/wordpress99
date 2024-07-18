<?php
/**
 * Scheduler class
 */

namespace underDEV\AdvancedCronManagerPRO;

use underDEV\Utils;
use underDEV\AdvancedCronManagerPRO\License\Manager;


class Scheduler {

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
	 * Schyntax Library class
	 * @var object
	 */
	public $schyntax_library;

	/**
	 * License Manager class
	 * @var Manager
	 */
	public $license_manager;

	/**
	 * Class constructor
	 */
	public function __construct( Utils\View $view, Utils\Ajax $ajax, SchyntaxLibrary $schyntax_library, Manager $license_manager ) {

		$this->view             = $view;
		$this->ajax             = $ajax;
		$this->schyntax_library = $schyntax_library;
		$this->license_manager  = $license_manager;

	}

	/**
	 * Add event form additional schedules
	 */
	public function add_event_form_schedules() {

		$this->view->set_var( 'schedules', $this->schyntax_library->get_schedules() );
		$this->view->get_view( 'forms/event-add-schedules' );

	}

	/**
	 * Add schedule form
	 */
	public function add_schedule_form() {

		$this->ajax->verify_nonce( 'acm/schedule/add' );

		ob_start();

		$this->view->get_view( 'forms/schedule-add' );

		$this->ajax->response( ob_get_clean() );

	}

	/**
	 * Add schedule form
	 */
	public function edit_schedule_form() {

		// phpcs:ignore
		$id = $_REQUEST['schedule'];

		// Bail if this is a regular schedule being edited. It's handled by the base plugin.
		if ( ! $this->schyntax_library->has( $id ) ) {
			return;
		}

		$this->ajax->verify_nonce( 'acm/schedule/edit/' . $id );

		ob_start();

		$this->view->set_var( 'id', $id );
		$this->view->set_var( 'schedule', $this->schyntax_library->get_schedule( $id ) );
		$this->view->get_view( 'forms/schedule-edit' );

		$this->ajax->response( ob_get_clean() );

	}

	/**
	 * Displays schyntax schedules table
	 */
	public function display_schedules_table() {

		$this->view->set_var( 'schedules', $this->schyntax_library->get_schedules() );
		$this->view->get_view( 'schyntax-schedules' );

	}

	/**
	 * Hides schyntax schedules from regular rendering
	 */
	public function hide_schyntax_schedule( $display, $schedule ) {

		if ( $this->schyntax_library->has( $schedule->slug ) ) {
			return false;
		}

		return $display;

	}

	/**
	 * Insert schedule from AJAX
	 */
	public function ajax_insert_schedule() {

		$this->ajax->verify_nonce( 'acm/schedule/insert' );

		if ( ! current_user_can( 'manage_options' ) ) {
			$this->ajax->response( false, array(
				__( "You're not allowed to do that.", 'advanced-cron-manager' ),
			) );
		}

		// phpcs:ignore
		$data   = wp_parse_args( $_REQUEST['data'], array() );
		$errors = array();

		// Bail if this is a regular schedule being added. It's handled by the base plugin.
		if ( ! isset( $data['schyntax'] ) ) {
			return;
		}

		if ( empty( $data['schyntax'] ) ) {
			$errors[] = __( 'Please, provide schyntax schedule', 'advanced-cron-manager' );
		}

		if ( empty( $data['schyntax-name'] ) ) {
			$errors[] = __( 'Please, provide a name for your Schedule', 'advanced-cron-manager' );
		}

		$result = $this->schyntax_library->insert(
			sanitize_text_field( trim( $data['schyntax-name'] ) ),
			sanitize_text_field( trim( $data['schyntax'] ) )
		);

		// Translators: schedule slug.
		$success = sprintf( __( '%s schedule has been added', 'advanced-cron-manager-pro' ), $data['schyntax-name'] );

		$this->ajax->response( $success, $errors );

	}

	/**
	 * Reschedules the Schyntax event
	 */
	public function reschedule_event( $shortcircut, $event, $wp_error ) {

		if ( ! $this->schyntax_library->has( $event->schedule ) ) {
			return $shortcircut;
		}

		$api_url = sprintf(
			'https://acm-schyntax.bracketspace.workers.dev/?%s',
			http_build_query( array(
				'license' => $this->license_manager->get_license_key(),
				'domain' => home_url(),
				'schedule' => $event->interval,
				'n' => 1,
				// 'after' =>
			) )
		);

		$response = wp_remote_get( $api_url, array(
			'headers' => array(
				'Accept' => 'application/json',
			)
		) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		$response_json = json_decode( $response['body'] );

		$next_occurence = strtotime( $response_json[0] );

		if ( false === $next_occurence ) {
			return false;
		}

		// Compensate the timezone offset if schedule has specific hour defined
		if ( preg_match( '/\b(h|hour|hourOfDay|hoursOfDay)\b/', $event->interval ) === 1 ) {
			$next_occurence -= get_option( 'gmt_offset' ) * 3600;
		}

		wp_schedule_event( $next_occurence, $event->schedule, $event->hook, $event->args, $wp_error );

		return true;

	}

	/**
	 * Edit schedule from AJAX
	 */
	public function ajax_edit_schedule() {

		$this->ajax->verify_nonce( 'acm/schedule/edit' );

		if ( ! current_user_can( 'manage_options' ) ) {
			$this->ajax->response( false, array(
				__( "You're not allowed to do that.", 'advanced-cron-manager' ),
			) );
		}

		// phpcs:ignore
		$data   = wp_parse_args( $_REQUEST['data'], array() );
		$errors = array();

		// Bail if this is a regular schedule being added. It's handled by the base plugin.
		if ( ! isset( $data['schyntax'] ) ) {
			return;
		}

		if ( ! $this->schyntax_library->has( $data['id'] ) ) {
			$errors[] = __( "Selected syntax doesn't exist anymore. Please refresh the page.", 'advanced-cron-manager-pro' );
		}

		if ( empty( $data['schyntax'] ) ) {
			$errors[] = __( 'Please, provide schyntax schedule', 'advanced-cron-manager-pro' );
		}

		if ( empty( $data['schyntax-name'] ) ) {
			$errors[] = __( 'Please, provide a name for your Schedule', 'advanced-cron-manager-pro' );
		}

		$result = $this->schyntax_library->insert(
			sanitize_text_field( trim( $data['schyntax-name'] ) ),
			sanitize_text_field( trim( $data['schyntax'] ) ),
			$data['id']
		);

		// Translators: schedule slug.
		$success = sprintf( __( '%s schedule has been saved', 'advanced-cron-manager-pro' ), $data['schyntax-name'] );

		$this->ajax->response( $success, $errors );

	}

	/**
	 * Remove schedule via AJAX
	 *
	 * @return void
	 */
	public function ajax_remove_schedule() {

		// phpcs:ignore
		$id = $_REQUEST['schedule'];

		// Bail if this is a regular schedule being removed. It's handled by the base plugin.
		if ( ! $this->schyntax_library->has( $id ) ) {
			return;
		}

		$this->ajax->verify_nonce( 'acm/schedule/remove/' . $id );

		if ( ! current_user_can( 'manage_options' ) ) {
			$this->ajax->response( false, array(
				__( "You're not allowed to do that.", 'advanced-cron-manager' ),
			) );
		}

		$result = $this->schyntax_library->remove( $id );

		if ( is_array( $result ) ) {
			$errors = $result;
		} else {
			$errors = array();
		}

		$this->ajax->response( __( 'Schedule has been removed', 'advanced-cron-manager-pro' ), $errors );

	}

}
