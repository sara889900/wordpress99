<?php
/**
 * Implementation info how to use debug tool
 */
?>

<p><?php esc_html_e( 'You can also log any information you want inside the event callback. Just use the action:', 'advanced-cron-manager' ); ?></p>

<code>
$my_var = 'value';<br>
<br>
add_action( 'advanced-cron-manager/log', function( $logger ) use ( $my_var ) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;$logger->log( 'My var is: ' . $my_var );<br>
} );
</code>
