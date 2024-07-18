<?php
/**
 * MemoryUsageTracker class
 */

namespace underDEV\AdvancedCronManagerPRO\Tracker;

use underDEV\AdvancedCronManagerPRO\Abstracts\Tracker as AbstractTracker;
use underDEV\AdvancedCronManagerPRO\Contract\CanTrackSingleValue;

/**
 * MemoryUsageTracker class
 */
class MemoryUsageTracker extends AbstractTracker implements CanTrackSingleValue {

	/**
	 * Tracker type
	 *
	 * @var string
	 */
	protected static $type = 'performance';

	/**
	 * Starting usage in bytes
	 *
	 * @var int
	 */
	protected $starting_usage;

	/**
	 * Usage in bytes
	 *
	 * @var int
	 */
	protected $usage;

	/**
	 * Peak usage in bytes
	 *
	 * @var int
	 */
	protected $peak_usage;
	
	/**
	 * Starts the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	public function track( $event ) {
		$this->starting_usage = memory_get_usage( true );
	}

	/**
	 * Resolves the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	public function resolve( $event ) {
		$this->usage      = max( memory_get_usage( true ) - $this->starting_usage, 0 );
		$this->peak_usage = memory_get_peak_usage();
	}

	/**
	 * Formats the tracker as a string
	 *
	 * @since  2.5.0
	 * @return string
	 */
	public function __toString() {
		return sprintf(
			__( 'Memory used: %s, peak: %s', 'advanced-cron-manager' ),
			static::format_memory( $this->usage ),
			static::format_memory( $this->peak_usage )
		);
	}

	/**
	 * Formats memory to human readable unit
	 *
	 * @since  2.5.0
	 * @param  int $bytes Bytes.
	 * @return string
	 */
	public static function format_memory( $bytes ) {
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
		$i     = intval( floor( log( $bytes, 1024 ) ) );

		if ( ! isset( $units[ $i ] ) || 0 === $bytes ) {
			return 'NaN';
		}

		return @round( $bytes / pow( 1024, $i ), 2 ) . ' ' . $units[ $i ];
	}

}
