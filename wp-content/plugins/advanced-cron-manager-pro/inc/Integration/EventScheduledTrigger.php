<?php
/**
 * Event scheduled trigger class
 */

namespace underDEV\AdvancedCronManagerPRO\Integration;

use BracketSpace\Notification\Abstracts\Trigger;
use BracketSpace\Notification\Defaults\MergeTag;

/**
 * Event scheduled trigger class
 */
class EventScheduledTrigger extends Trigger {

	/**
	 * Constructor
	 */
	public function __construct() {

		parent::__construct(
			'advanced-cron-manager/event-scheduled',
			__( 'Event scheduled', 'advanced-cron-manager' )
		);

		$this->add_action( 'advanced-cron-manager/event/scheduled', 10, 4 );

		$this->set_group( __( 'Advanced Cron Manager', 'advanced-cron-manager' ) );

		$this->set_description(
			__( 'Fires when cron even is scheduled with ACM', 'advanced-cron-manager' )
		);

	}

	/**
	 * Assigns action callback args to object
	 * Return `false` if you want to abort the trigger execution
	 *
	 * You can use the action method arguments as usually.
	 *
	 * @return mixed void or false if no notifications should be sent
	 */
	public function context( $hook, $execution_timestamp, $schedule, $args ) {
		$this->hook               = $hook;
		$this->schedule           = $schedule->slug;
		$this->args               = $args;
		$this->datetime_next_call = $execution_timestamp;
		$this->datetime_scheduled = time();
	}

	/**
	 * Registers attached merge tags
	 *
	 * @return void
	 */
	public function merge_tags() {

		$this->add_merge_tag( new MergeTag\StringTag( array(
			'slug'        => 'event_hook',
			'name'        => __( 'Event hook', 'advanced-cron-manager' ),
			'description' => 'cron_hook',
			'example'     => true,
			'resolver'    => function( $trigger ) {
				return $trigger->hook;
			},
		) ) );

		$this->add_merge_tag( new MergeTag\StringTag( array(
			'slug'        => 'event_schedule',
			'name'        => __( 'Event schedule', 'advanced-cron-manager' ),
			'description' => 'daily',
			'example'     => true,
			'resolver'    => function( $trigger ) {
				return $trigger->schedule;
			},
		) ) );

		$this->add_merge_tag( new MergeTag\StringTag( array(
			'slug'        => 'event_args',
			'name'        => __( 'Event args', 'advanced-cron-manager' ),
			'description' => '1, single',
			'example'     => true,
			'resolver'    => function( $trigger ) {
				return implode( ', ', $trigger->args );
			},
		) ) );

		$this->add_merge_tag( new MergeTag\DateTime\DateTime( array(
			'slug' => 'datetime_scheduled',
			'name' => __( 'Date and Time scheduled', 'advanced-cron-manager' ),
		) ) );

		$this->add_merge_tag( new MergeTag\DateTime\DateTime( array(
			'slug' => 'datetime_next_call',
			'name' => __( 'Date and Time of the next call', 'advanced-cron-manager' ),
		) ) );

    }

}
