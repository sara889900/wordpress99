<?php
/**
 * CanTrackMultiValue contract class
 */

namespace underDEV\AdvancedCronManagerPRO\Contract;

interface CanTrackMultiValue {

	/**
	 * Formats the tracker as an array
	 *
	 * @since  2.5.0
	 * @return string
	 */
	public function to_array();

}
