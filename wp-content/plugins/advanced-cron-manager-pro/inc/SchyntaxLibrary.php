<?php
/**
 * Schedules Library class
 * Handles DB operations on schedules
 *
 * @package advanced-cron-manager-pro
 */

namespace underDEV\AdvancedCronManagerPRO;

use underDEV\Utils;

/**
 * Schedules Library class
 */
class SchyntaxLibrary {

	/**
	 * Option name
	 *
	 * @var string
	 */
	private $option_name = 'acm_schyntax_schedules';

	/**
	 * Saved schedules
	 * Format: unique ID => Schyntax definition
	 *
	 * @var array
	 */
	private $schedules = array();

	/**
	 * Gets all saved schedules
	 * Supports lazy loading
	 *
	 * @param  boolean $force if refresh stored schedules.
	 * @return array          saved schedules
	 */
	public function get_schedules( $force = false ) {

		if ( empty( $this->schedules ) || $force ) {
			$this->schedules = get_option( $this->option_name, array() );
		}

		return $this->schedules;

	}

	/**
	 * Gets single schedule
	 *
	 * @param  string $id Schedule id.
	 * @return mixed        Schedule object on success or false
	 */
	public function get_schedule( $id = '' ) {

		if ( empty( $id ) ) {
			trigger_error( 'Schedule id cannot be empty' );
		}

		$schedules = $this->get_schedules();

		return isset( $schedules[ $id ] ) ? $schedules[ $id ] : false;

	}

	/**
	 * Check if schedule is saved by ACM
	 *
	 * @param  string $id schedule slug.
	 * @return boolean                true if yes
	 */
	public function has( $id ) {
		$schedules = $this->get_schedules();
		return isset( $schedules[ $id ] );
	}

	/**
	 * Registers all schedules
	 *
	 * @param  array $schedules Schedules already registered in WP.
	 * @return array            all Schedules
	 */
	public function add_cron_schedule_definition( $schedules ) {

		foreach ( $this->get_schedules() as $id => $schedule ) {
			$schedules[ $id ] = array(
				'interval' => $schedule['schedule'],
				'display'  => $schedule['name'],
			);
		}

		return $schedules;

	}

	/**
	 * Inserts new schedule in the database
	 * It also refreshed the current schedules
	 *
	 * @param  string $name     Display name.
	 * @param  string $schyntax Schyntax schedule definition.
	 * @param  string $id       Schedule ID, if provided will update the schedule.
	 * @return mixed            true on success or array with errors
	 */
	public function insert( $name, $schyntax, $id = null ) {

		$schedules = $this->get_schedules();

		if ( empty( $id ) || ! isset( $schedules[$id] ) ) {
			$id = sprintf( 'schyntax:%s', uniqid() );
		}

		$schedules[ $id ] = array(
			'name'     => $name,
			'schedule' => $schyntax,
		);

		update_option( $this->option_name, $schedules );

		return true;

	}

	/**
	 * Inserts new schedule in the database
	 * It also refreshed the current schedules
	 *
	 * @param  string $id Schedule slug.
	 * @return mixed        true on success or array with errors
	 */
	public function remove( $id ) {

		$errors = array();

		if ( ! $this->has( $id ) ) {
			// Translators: schedule slug.
			$errors[] = sprintf( __( 'Schedule with ID "%s" cannot be removed because it doesn\'t exists', 'advanced-cron-manager-pro' ), $id );
		}

		if ( ! empty( $errors ) ) {
			return $errors;
		}

		$schedules = get_option( $this->option_name, array() );
		unset( $schedules[ $id ] );
		unset( $this->schedules[ $id ] );

		update_option( $this->option_name, $schedules );

		return true;

	}

}
