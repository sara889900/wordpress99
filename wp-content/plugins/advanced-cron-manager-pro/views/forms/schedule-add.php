<?php
/**
 * Add schedule form
 *
 * @package advanced-cron-manager-pro
 */

?>

<h3><?php esc_html_e( 'New schedule', 'advanced-cron-manager' ); ?></h3>

<nav id="acm-schedule-form-tabs" class="nav-tab-wrapper">
	<a href="#acm-schedule-form-tab-desc" class="nav-tab nav-tab-active"><?php esc_html_e( 'Descriptive', 'advanced-cron-manager-pro' ); ?></a>
	<a href="#acm-schedule-form-tab-classic" class="nav-tab"><?php esc_html_e( 'Classic', 'advanced-cron-manager-pro' ); ?></a>
</nav>

<div class="tab-content">
	<form class="schedule-add schedule-form-tab active" id="acm-schedule-form-tab-desc">

		<?php wp_nonce_field( 'acm/schedule/insert', 'nonce', false ); ?>
		<p><a href="javascript:void(0)" class="generate-shyntax">
			Generate Schyntax <svg xmlns="http://www.w3.org/2000/svg" data-name="Your Icon" viewBox="0 0 100 125" x="0px" y="0px"><path fill="#2271b1" d="m90.64,59.09l-16.25-7.09c-3.93-1.71-7.06-4.85-8.77-8.77l-7.09-16.25c-.55-1.26-2.34-1.26-2.89,0l-7.09,16.25c-1.71,3.93-4.85,7.06-8.77,8.77l-16.27,7.1c-1.26.55-1.26,2.33,0,2.88l16.55,7.32c3.92,1.73,7.04,4.88,8.73,8.82l6.86,15.94c.54,1.27,2.34,1.27,2.89,0l7.08-16.22c1.71-3.93,4.85-7.06,8.77-8.77l16.25-7.09c1.26-.55,1.26-2.34,0-2.89Z"/><path fill="#2271b1" d="m25.28,48.51l3.32-7.61c.8-1.84,2.27-3.31,4.11-4.11l7.62-3.32c.59-.26.59-1.1,0-1.35l-7.62-3.32c-1.84-.8-3.31-2.27-4.11-4.11l-3.32-7.62c-.26-.59-1.1-.59-1.35,0l-3.32,7.62c-.8,1.84-2.27,3.31-4.11,4.11l-7.63,3.33c-.59.26-.59,1.09,0,1.35l7.76,3.43c1.84.81,3.3,2.29,4.09,4.13l3.22,7.47c.26.59,1.1.6,1.35,0Z"/><path fill="#2271b1" d="m39.89,13.95l4.12,1.82c.98.43,1.75,1.22,2.17,2.19l1.71,3.97c.14.32.58.32.72,0l1.76-4.04c.43-.98,1.21-1.76,2.18-2.18l4.04-1.76c.31-.14.31-.58,0-.72l-4.04-1.76c-.98-.43-1.76-1.21-2.18-2.18l-1.76-4.04c-.14-.31-.58-.31-.72,0l-1.76,4.04c-.43.98-1.21,1.76-2.18,2.18l-4.05,1.77c-.31.14-.31.58,0,.72Z"/>
		</a></p>

		<div class="schyntax-generator">
			<textarea id="schedule-schyntax-prompt" class="widefat" placeholder="<?php esc_attr_e( 'Describe your schedule, like: every last working day of month', 'advanced-cron-manager-pro' ); ?>"></textarea>
			<button class="button button-small button-secondary alignright"><?php esc_html_e( 'Generate', 'advanced-cron-manager-pro' ); ?></button> <span class="spinner"></span>
		</div>

		<div class="clear">
			<label for="schedule-schyntax"><?php esc_html_e( 'Schyntax', 'advanced-cron-manager' ); ?></label>
			<textarea id="schedule-schyntax" name="schyntax" class="widefat"></textarea>
			<p class="description"><a href="https://github.com/schyntax/schyntax" target="_blank"><?php esc_html_e( 'Read more about Schyntax', 'advanced-cron-manager-pro' ); ?></a>. <?php esc_html_e( 'Schedules are relative to your timezone.', 'advanced-cron-manager-pro' ); ?></p>
		</div>

		<div class="clear">
			<label for="schedule-schyntax"><?php esc_html_e( 'Name', 'advanced-cron-manager' ); ?></label>
			<input type="text" name="schyntax-name" class="widefat schyntax-name-input">
			<p class="description"><?php esc_html_e( 'Easily identifiable name for your reference.', 'advanced-cron-manager-pro' ); ?></p>
		</div>

		<div class="clear schedule-next-occurences">
			<p><strong><?php esc_html_e( 'Next occurrences', 'advanced-cron-manager-pro' ); ?></strong></p>
			<p class="result"></p>
			<ul class="occurences-list"></ul>
			<span class="spinner"></span>
		</div>

		<div class="submit-row">
			<button type="submit" id="schedule-add-schyntax-button" class="button button-primary send-form" disabled><?php esc_html_e( 'Add schedule', 'advanced-cron-manager' ); ?></button>
		</div>
		<span class="spinner"></span>
	</form>

	<form class="schedule-add schedule-form-tab" id="acm-schedule-form-tab-classic">

		<?php wp_nonce_field( 'acm/schedule/insert', 'nonce', false ); ?>

		<label for="schedule-name"><?php esc_html_e( 'Display name', 'advanced-cron-manager' ); ?></label>
		<input type="text" id="schedule-name" name="name" class="widefat">

		<label for="schedule-slug"><?php esc_html_e( 'Slug', 'advanced-cron-manager' ); ?></label>
		<input type="text" id="schedule-slug" name="slug" class="widefat">

		<label><?php esc_html_e( 'Interval', 'advanced-cron-manager' ); ?></label>
		<table>
			<tr>
				<td><?php esc_html_e( 'Days', 'advanced-cron-manager' ); ?>:</td>
				<td><input type="number" id="schedule-interval" min="0" value="0" class="spinbox days"></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Hours', 'advanced-cron-manager' ); ?>:</td>
				<td><input type="number" id="schedule-interval" min="0" max="24" value="0" class="spinbox hours"></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Minutes', 'advanced-cron-manager' ); ?>:</td>
				<td><input type="number" id="schedule-interval" min="0" max="60" value="0" class="spinbox minutes"></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Seconds', 'advanced-cron-manager' ); ?>:</td>
				<td><input type="number" id="schedule-interval" min="0" max="60" value="0" class="spinbox seconds"></td>
			</tr>
		</table>

		<div class="total-seconds"><?php esc_html_e( 'Total seconds:', 'advanced-cron-manager' ); ?> <span>0</span></div>
		<input type="hidden" name="interval" class="interval-input" value="0">

		<div class="submit-row">
			<button type="submit" class="button button-primary send-form"><?php esc_html_e( 'Add schedule', 'advanced-cron-manager' ); ?></button>
		</div>
		<span class="spinner"></span>

	</form>
</div>
