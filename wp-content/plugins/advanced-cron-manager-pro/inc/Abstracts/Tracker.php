<?php
/**
 * Tracker abstract class
 */

namespace underDEV\AdvancedCronManagerPRO\Abstracts;

use underDEV\AdvancedCronManagerPRO\Contract;

/**
 * Tracker class
 */
abstract class Tracker {

	/**
	 * Was the tracker executed?
	 *
	 * @var boolean
	 */
	protected $dirty = false;

	/**
	 * Does the tracker finished tracking?
	 *
	 * @var boolean
	 */
	protected $finished = false;
	
	/**
	 * Starts the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	abstract public function track( $event );

	/**
	 * Resolves the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	abstract public function resolve( $event );

	/**
	 * Finishes the tracking
	 *
	 * @since  2.5.0
	 * @param  Event $event Event object.
	 * @return void
	 */
	public function finish( $event ) {
		if ( $this->finished ) {
			return;
		}

		$this->resolve( $event );

		$data = $this->get_data();

		$this->dirty    = ! empty( $data );
		$this->finished = true;
	}

	/**
	 * Gets tracker data
	 *
	 * @since  2.5.0
	 * @return mixed
	 */
	public function get_data() {
		$data = null;

		if ( $this instanceof Contract\CanTrackSingleValue ) {
			$data = (string) $this;
		} elseif ( $this instanceof Contract\CanTrackMultiValue ) {
			$data = $this->to_array();
		}

		return $data;
	}

	/**
	 * Get tracker type
	 *
	 * @since  2.5.0
	 * @return string
	 */
	public static final function get_type() {
        return static::$type;
    }

	/**
	 * Checks if tracker is dirty.
	 *
	 * @since  2.5.0
	 * @return bool
	 */
	public function is_dirty() {
        return $this->dirty;
    }

}
