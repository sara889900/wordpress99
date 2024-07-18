<?php
/**
 * Reschedule form part
 */

$event = $this->get_var( 'event' );
$schedules = $this->get_var( 'schedules' );
$current_schedule = $this->get_var( 'current_schedule' );

?>

<h3><?php esc_html_e( 'Reschedule event', 'advanced-cron-manager-pro' ); ?></h3>

<form class="event-reschedule">

	<?php wp_nonce_field( 'acm/event/reschedule/' . $event->hash, 'nonce', false ); ?>

	<input type="hidden" name="event_hash" value="<?php echo esc_attr( $event->hash ); ?>">

	<strong><?php esc_html_e( 'Event hook', 'advanced-cron-manager-pro' ); ?></strong><br>
	<?php echo esc_html( $event->hook ); ?><br>

	<br>

	<label for="event-execution"><?php esc_html_e( 'Next execution', 'advanced-cron-manager-pro' ); ?></label>
	<p class="description"><?php esc_html_e( 'When past date will be provided or left empty, event will be executed in the next queue', 'advanced-cron-manager-pro' ); ?></p>
	<input type="datetime-local" id="event-execution" name="execution" class="widefat"></input>
	<input type="hidden" id="event-offset" name="execution_offset"></input>

    <label for="event-schedule"><?php esc_html_e( 'Schedule', 'advanced-cron-manager' ); ?></label>
    <select id="event-schedule" class="widefat" name="schedule">
        <option value="<?php echo esc_attr( $event->schedule ); ?>">
            <?php
                echo esc_html( sprintf('%s (%s)', $current_schedule->label, $current_schedule->slug)  );
            ?>
        </option>
        <?php foreach ( $schedules->get_schedules() as $schedule ) : ?>
            <?php if ($current_schedule->label === $schedule->label ) { continue; } ?>
            <option value="<?php echo esc_attr( $schedule->slug ); ?>"><?php echo esc_html( $schedule->label ); ?> (<?php echo esc_html( $schedule->slug ); ?>)</option>
        <?php endforeach ?>
        <?php do_action( 'advanced-cron-manager/screen/form/event/add/schedules', $this ); ?>
    </select>

	<div class="submit-row">
		<span class="spinner"></span>
		<input type="submit" class="button button-primary send-form" value="<?php esc_attr_e( 'Reschedule event', 'advanced-cron-manager-pro' ); ?>">
	</div>

</form>
