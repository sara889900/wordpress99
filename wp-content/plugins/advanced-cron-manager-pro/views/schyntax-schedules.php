<?php
/**
 * Schyntax schedules table
 */

$schedules = $this->get_var( 'schedules' );

?>

<?php foreach ($schedules as $id => $schedule) : ?>
	<div class="single-schedule">
		<div class="column label">
			<?php echo esc_html( $schedule['name'] ); ?>
			<div class="code"><?php echo esc_html( $schedule['schedule'] ); ?></div>
		</div>
		<div class="column actions">
			<a href="#" data-nonce="<?php echo esc_attr( wp_create_nonce( 'acm/schedule/edit/' . $id ) ); ?>" data-schedule="<?php echo esc_attr( $id ); ?>" class="edit-schedule dashicons dashicons-edit" title="<?php esc_attr_e( 'Edit', 'advanced-cron-manager' ); ?>">
				<span><?php esc_html_e( 'Edit', 'advanced-cron-manager' ); ?></span>
			</a>
			<a href="#" data-nonce="<?php echo esc_attr( wp_create_nonce( 'acm/schedule/remove/' . $id ) ); ?>" data-schedule="<?php echo esc_attr( $id ); ?>" class="remove-schedule dashicons dashicons-trash" title="<?php esc_attr_e( 'Remove', 'advanced-cron-manager' ); ?>">
				<span><?php esc_html_e( 'Remove', 'advanced-cron-manager' ); ?></span>
				</a>
		</div>
	</div>
<?php endforeach ?>
