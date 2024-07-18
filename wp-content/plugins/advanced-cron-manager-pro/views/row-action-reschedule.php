<?php
/**
 * Reschedule row action part
 */

$event = $this->get_var( 'event' );

?>

<span class="reschedule">
	<a href="#" data-nonce="<?php echo $event->nonce( 'reschedule' ); ?>" data-event="<?php echo esc_attr( $event->hash ); ?>" class="reschedule-event"><?php esc_html_e( 'Reschedule', 'advanced-cron-manager-pro' ); ?></a> |
</span>
