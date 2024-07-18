<?php
/**
 * Event add schedules
 *
 * @package advanced-cron-manager-pro
 */

$schedules = $this->get_var( 'schedules' );

?>

<optgroup label="<?php esc_attr_e( 'Schyntax Schedules', 'advanced-cron-manager-pro' ); ?>">
	<?php foreach ( $schedules as $id => $schedule ) : ?>
		<option value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $schedule['name'] ); ?></option>
	<?php endforeach ?>
</optgroup>
