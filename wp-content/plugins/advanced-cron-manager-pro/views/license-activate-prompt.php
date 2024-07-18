<?php
/**
 * License activate prompt
 */

$license = $this->get_var( 'license' );

$renewal_link = sprintf(
	'https://bracketspace.com/payment/?edd_license_key=%s&download_id=27',
	$this->get_var( 'license_key' )
);

?>

<div class="notice notice-warning is-dismissible">
	<?php if ( $license->license === 'expired' ): ?>
		<p>
			<?php
				printf(
					esc_html__(
						'Your Advanced Cron Manager PRO license expired, and your website %smight be in danger%s.',
						'advanced-cron-manager'
					),
					'<strong>',
					'</strong>',
				);
			?>
			<a class="button button-small button-secondary" target="_blank" href="<?php echo esc_attr( $renewal_link ); ?>">
				<?php esc_html_e( 'Renew your license now', 'advanced-cron-manager' ); ?>
			</a>
			or
			<a href="https://bracketspace.com/expired-license/" target="_blank">
				<?php esc_html_e( 'Read more about expired license', 'advanced-cron-manager' ); ?>
			</a>
		</p>
	<?php else: ?>
		<p><?php esc_html_e( 'Please activate the license of Advanced Cron Manager PRO in the plugin\'s settings screen sidebar.', 'advanced-cron-manager' ) ?></p>
	<?php endif ?>
</div>
