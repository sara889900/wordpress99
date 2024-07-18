<?php
/**
 * Listeners display
 */

$listeners = $this->get_var('listeners');

if ( empty($listeners) ) {
	esc_html_e('⚠️ No functions are listening for this event.');
	return;
}

?>

<?php foreach ($listeners as $listener) : ?>
	<code><?php echo esc_attr($listener['callback']['name']) ?></code>
	<p><?php echo sprintf('Defined in <i>%s</i> line %d', $listener['callback']['details']['file'], $listener['callback']['details']['line'])?></p>
	<hr>
<?php endforeach; ?>
