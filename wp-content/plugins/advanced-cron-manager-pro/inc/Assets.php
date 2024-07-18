<?php
/**
 * Assets class
 * Loads plugin assets
 */

namespace underDEV\AdvancedCronManagerPRO;
use underDEV\Utils;
use underDEV\AdvancedCronManager\AdminScreen;
use underDEV\AdvancedCronManagerPRO\License\Manager;

class Assets {

	/**
	 * Current plugin version
	 * @var string
	 */
	public $plugin_version;

	/**
	 * Files class
	 * @var object
	 */
	public $files;

	/**
	 * License Manager class
	 * @var Manager
	 */
	public $license_manager;

	public function __construct( $version, Utils\Files $files, Manager $license_manager ) {

		$this->plugin_version  = $version;
		$this->files           = $files;
		$this->license_manager = $license_manager;

	}

	/**
	 * Enqueue admin scripts
	 * @return void
	 */
	public function enqueue_admin( $current_page_hook ) {

		$code_editor_settings = wp_enqueue_code_editor( array(
			'type' => 'text/plain',
			'codemirror' => array(
				'lineNumbers' => false,
				'styleActiveLine' => false,
				'autoCloseBrackets' => true,
			),
		) );

		wp_enqueue_style( 'advanced-cron-manager-pro', $this->files->asset_url( 'css', 'style.css' ), array( 'advanced-cron-manager' ), $this->plugin_version );
		wp_enqueue_script( 'advanced-cron-manager-pro', $this->files->asset_url( 'js', 'scripts.min.js' ), array( 'advanced-cron-manager' ), $this->plugin_version, true );

		wp_localize_script( 'advanced-cron-manager-pro', 'advanced_cron_manager_pro', array(
			'domain'          => home_url(),
			'license_key'     => $this->license_manager->get_license_key(),
			'schyntax_editor' => wp_json_encode( $code_editor_settings ),
			'time_offset'     => get_option( 'gmt_offset' ),
			'i18n'            => array(
				'saving'       => __( 'Saving...', 'advanced-cron-manager' ),
				'deactivating' => __( 'Deactivating...', 'advanced-cron-manager' ),
				'activated'    => __( 'License activated', 'advanced-cron-manager' ),
				'deactivated'  => __( 'License deactivated', 'advanced-cron-manager' ),
				'loading'      => __( 'Loading...', 'advanced-cron-manager' ),
				'error'        => __( 'There was an unspecified error, please try again later', 'advanced-cron-manager-pro' ),
				'schyntax'     => array(
					'status_400' => __( "It doesn't seem to be a correct schedule definition", 'advanced-cron-manager-pro' ),
					'status_401' => __( 'Error. Please check if your license is active', 'advanced-cron-manager-pro' ),
					'status_429' => __( "You're sending too many queries, try again in a minute", 'advanced-cron-manager-pro' ),
				),
				'schedule'     => array(
					'status_400' => __( "Please provide correct schyntax", 'advanced-cron-manager-pro' ),
					'status_401' => __( 'Error. Please check if your license is active', 'advanced-cron-manager-pro' ),
					'status_429' => __( "You're sending too many queries, try again in a minute", 'advanced-cron-manager-pro' ),
				),
			)
		) );

	}

}
