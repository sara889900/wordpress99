<?php
/**
 * Plugin Name: Advanced Cron Manager PRO
 * Description: Log cron execution times, errors and performance
 * Version: 2.7.2
 * Author: BracketSpace
 * Author URI: https://bracketspace.com
 * License: GPL3
 * Text Domain: advanced-cron-manager-pro
 */

/**
 * Fire up Composer's autoloader
 */
require_once __DIR__ . '/vendor/autoload.php';

$requirements = new underDEV_Requirements( __( 'Advanced Cron Manager PRO', 'advanced-cron-manager' ), array(
	'php'         => '5.4',
	'wp'          => '3.6',
	'plugins'     => array(
		'advanced-cron-manager/advanced-cron-manager.php' => array(
			'name' => 'Advanced Cron Manager',
			'version' => '2.5.4'
		),
	),
	'old_plugins' => array(
		'advanced-cron-manager/acm.php' => array(
			'name' => 'Advanced Cron Manager',
			'version' => '2.0'
		),
	)
) );

/**
 * Check if old plugins are active
 * @param  array $plugins array with plugins,
 *                        where key is the plugin file and value is the version
 * @return void
 */
function acm_pro_check_old_plugins( $plugins, $r ) {

	foreach ( $plugins as $plugin_file => $plugin_data ) {

		if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
			continue;
		}

		$plugin_api_data = @get_file_data( WP_PLUGIN_DIR . '/' . $plugin_file , array( 'Version' ) );

		if ( ! isset( $plugin_api_data[0] ) ) {
			continue;
		}

		$old_plugin_version = $plugin_api_data[0];

		if ( ! empty( $old_plugin_version ) && version_compare( $old_plugin_version, $plugin_data['version'], '<' ) ) {
			$r->add_error( sprintf( '%s plugin at least in version %s', $plugin_data['name'], $plugin_data['version'] ) );
		}

	}

}

if ( method_exists( $requirements, 'add_check' ) ) {
	$requirements->add_check( 'old_plugins', 'acm_pro_check_old_plugins' );
}

if ( ! $requirements->satisfied() ) {

	add_action( 'admin_notices', array( $requirements, 'notice' ) );
	return;

}

/**
 * Require EDD Plugin updater
 */
if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	require_once( 'inc/License/EDD_SL_Plugin_Updater.php' );
}

add_action( 'plugins_loaded', 'advanced_cron_manager_pro_bootstrap' );

function advanced_cron_manager_pro_bootstrap() {

	$plugin_version = '2.7.2';
	$plugin_file    = __FILE__;

	/**
	 * Instances and Closures
	 */

	$files = new underDEV\Utils\Files( $plugin_file );

	$view = function() use ( $files ) {
		return new underDEV\Utils\View( $files );
	};

	$ajax = function() {
		return new underDEV\Utils\Ajax;
	};

	$schedules_library = new underDEV\AdvancedCronManager\Cron\SchedulesLibrary( $ajax() );
	$schyntax_library = new underDEV\AdvancedCronManagerPRO\SchyntaxLibrary();

	$schedules = function() use ( $schedules_library ) {
		return new underDEV\AdvancedCronManager\Cron\Schedules( $schedules_library );
	};

	$events = function() use ( $schedules ) {
		return new underDEV\AdvancedCronManager\Cron\Events( $schedules() );
	};

	$integration_notification = function() use ( $view ) {
		return new underDEV\AdvancedCronManagerPRO\Integration\Notification( $view() );
	};

	$license_manager = function() use ( $ajax ) {
		return new underDEV\AdvancedCronManagerPRO\License\Manager( $ajax() );
	};

	$assets = new underDEV\AdvancedCronManagerPRO\Assets( $plugin_version, $files, $license_manager() );

	$license_settings = function() use ( $view, $license_manager ) {
		return new underDEV\AdvancedCronManagerPRO\License\Settings( $view(), $license_manager() );
	};

	$updater = new underDEV\AdvancedCronManagerPRO\Updater( $plugin_version, $plugin_file, $license_manager() );

	$database = function() {
		return new underDEV\AdvancedCronManagerPRO\Database();
	};

	$log_options = function() use ( $database, $view, $ajax, $license_manager ) {
		return new underDEV\AdvancedCronManagerPRO\LogOptions( $database(), $view(), $ajax(), $license_manager() );
	};

	$logs_library = function() use ( $database, $log_options ) {
		return new underDEV\AdvancedCronManagerPRO\LogsLibrary( $database(), $log_options() );
	};

	$listeners_library = function() {
		return new underDEV\AdvancedCronManagerPRO\ListenersLibrary();
	};

	$logger = new underDEV\AdvancedCronManagerPRO\Logger( $events(), $logs_library(), $license_manager(), $log_options() );

	$log_displayer = function() use ( $view, $ajax, $logs_library, $listeners_library, $events, $log_options ) {
		return new underDEV\AdvancedCronManagerPRO\LogDisplayer( $view(), $ajax(), $logs_library(), $listeners_library(), $events(), $log_options() );
	};

	$rescheduler = function() use ( $view, $ajax, $events, $schedules ) {
		return new underDEV\AdvancedCronManagerPRO\Rescheduler( $view(), $ajax(), $events(), $schedules() );
	};

	$scheduler = function() use ( $view, $ajax, $schyntax_library, $license_manager ) {
		return new underDEV\AdvancedCronManagerPRO\Scheduler( $view(), $ajax(), $schyntax_library, $license_manager() );
	};

	/**
	 * Actions
	 */

	// Check if plugin needs upgrading
	add_action( 'advanced-cron-manager/screen/enqueue', array( $updater, 'upgrade' ), 10, 1 );

	// Check for updates
	add_filter( 'admin_init', array( $updater, 'update' ) );

	// Install database tables
	add_filter( 'plugins_loaded', array( $database(), 'install' ), 20 );

	// Add scripts
	add_action( 'advanced-cron-manager/screen/enqueue', array( $assets, 'enqueue_admin' ), 10, 1 );

	// Disable default logs tab
	add_filter( 'advanced-cron-manager/screen/event/details/tabs/logs/display', '__return_false', 10, 1 );

    // Disable default listeners tab
    add_filter( 'advanced-cron-manager/screen/event/details/tabs/listeners/display', '__return_false', 10, 1 );

	// Display logs tab
	add_action( 'advanced-cron-manager/screen/event/details/tab/logs', array( $log_displayer(), 'display_tab' ), 10, 1 );

	// Add info in implementation tab
	add_action( 'advanced-cron-manager/screen/event/details/tab/implementation', array( $log_displayer(), 'display_implementation' ), 20, 1 );

    // Add listeners in listeners tab
    add_action( 'advanced-cron-manager/screen/event/details/tab/listeners', array( $log_displayer(), 'display_listeners' ), 30, 1 );

	// Add logs section
	add_action( 'advanced-cron-manager/screen/main', array( $log_displayer(), 'display_section' ), 30, 1 );

	// Reload logs on ajax
	add_action( 'wp_ajax_acm/logs/refresh', array( $log_displayer(), 'refresh_logs' ) );

	// Load more logs
	add_action( 'wp_ajax_acm/logs/load_more', array( $log_displayer(), 'load_more' ) );

	// Add sidebar section parts on the admin screen
	add_action( 'advanced-cron-manager/screen/sidebar', array( $license_settings(), 'load_license_settings_part' ), 20, 1 );
	add_action( 'advanced-cron-manager/screen/sidebar', array( $log_options(), 'load_log_settings_part' ), 30, 1 );

	// License actions
	add_action( 'wp_ajax_acm/license/activate', array( $license_manager(), 'ajax_activate' ) );
	add_action( 'wp_ajax_acm/license/deactivate', array( $license_manager(), 'ajax_deactivate' ) );
	add_action( 'admin_notices', array( $license_settings(), 'display_activate_prompt' ) );

	// Log options
	add_action( 'wp_ajax_acm/logs/settings/save', array( $log_options(), 'save_settings' ) );

	// Add actions observer
	// if ( wp_doing_cron() ) {
		add_filter( 'plugins_loaded', array( $logger, 'add_actions' ), 20 );
		add_action( 'shutdown', array( $logger, 'shutdown' ), -10 );
	// }

	// Integration with Notification plugin
	add_action( 'notification/init', function() {
		// Integration requires Notification v8 or later.
		if ( ! class_exists( 'BracketSpace\Notification\Register' ) ) {
			return;
		}

		BracketSpace\Notification\Register::trigger( new underDEV\AdvancedCronManagerPRO\Integration\CronErrorTrigger() );
		BracketSpace\Notification\Register::trigger( new underDEV\AdvancedCronManagerPRO\Integration\EventUnscheduledTrigger() );
		BracketSpace\Notification\Register::trigger( new underDEV\AdvancedCronManagerPRO\Integration\EventScheduledTrigger() );
	} );

	// Scheduler
	add_action( 'wp_ajax_acm/schedule/add/form', array( $scheduler(), 'add_schedule_form' ), 5 );
	add_action( 'wp_ajax_acm/schedule/edit/form', array( $scheduler(), 'edit_schedule_form' ), 5 );
	add_action( 'wp_ajax_acm/schedule/insert', array( $scheduler(), 'ajax_insert_schedule' ), 5 );
	add_action( 'wp_ajax_acm/schedule/edit', array( $scheduler(), 'ajax_edit_schedule' ), 5 );
	add_action( 'wp_ajax_acm/schedule/remove', array( $scheduler(), 'ajax_remove_schedule' ), 5 );

	add_action( 'advanced-cron-manager/screen/sidebar/shedules/display', array( $scheduler(), 'hide_schyntax_schedule' ), 10, 2 );
	add_action( 'advanced-cron-manager/screen/sidebar/shedules/after', array( $scheduler(), 'display_schedules_table' ) );

	add_filter( 'cron_schedules', array( $schyntax_library, 'add_cron_schedule_definition' ) );
	add_action( 'wp_ajax_acm/event/add/form', array( $scheduler(), 'add_event_form' ) );

	add_filter( 'pre_reschedule_event', array( $scheduler(), 'reschedule_event' ), 1, 3 );

	// Rescheduler
	add_action( 'advanced-cron-manager/screen/event/row/actions', array( $rescheduler(), 'display_row_action' ) );
	add_action( 'wp_ajax_acm/event/reschedule/form', array( $rescheduler(), 'reschedule_event_form' ) );
	add_action( 'wp_ajax_acm/event/reschedule', array(  $rescheduler(), 'reschedule' ) );

}
