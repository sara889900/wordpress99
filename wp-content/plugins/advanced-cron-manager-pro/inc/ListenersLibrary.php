<?php
/**
 * ListenersLibrary class
 */

namespace underDEV\AdvancedCronManagerPRO;

class ListenersLibrary
{
	/**
	 * Takes callbacks from action name
	 * @param  string $name action name
	 * @return array
	 */
	public function get_listeners( $name ) {
		global $wp_filter;
		$actions = array();

		if ( isset( $wp_filter[ $name ] ) ) {
			$action = $wp_filter[ $name ];

			foreach ( $action as $priority => $callbacks ) {
				foreach ( $callbacks as $callback ) {

						if (
							!$callback['function'] instanceof \Closure &&
							$callback['function'][0] instanceof EventCapture
						) {
							break;
						}

					$callback = $this->formatted_callback( $callback );

					$actions[] = array(
						'priority' => $priority,
						'callback' => $callback,
					);
				}
			}
		}

		return $actions;
	}

	/**
	 * Returns detailed callback
	 * @param  array $callback callback
	 * @return array
	 */
	private function formatted_callback(array $callback )
	{
		$callback_function = $callback['function'];

		if ( is_string( $callback_function ) && (strrpos($callback_function, '::') !== false) ) {
			$callback_function = explode( '::', $callback_function );
		}

		if ( is_array( $callback_function ) ) {
			if ( is_object( $callback_function[0] ) ) {
				$class  = get_class( $callback_function[0] );
				$access = '->';
			} else {
				$class  = $callback_function[0];
				$access = '::';
			}

			$reflection = new \ReflectionMethod($callback_function[0], $callback_function[1]);

			$callback['name'] = $class . $access . $callback_function[1] . '()';
		} elseif ( is_object( $callback_function ) ) {
			$reflection = new \ReflectionFunction($callback_function);

			if ( is_a( $callback_function, 'Closure' ) ) {
				$callback['name'] = 'Closure';
			} else {
				$class = get_class( $callback_function );

				$callback['name'] = $class . '->__invoke()';
			}
		} else {
			$reflection = new \ReflectionFunction($callback_function);

			$callback['name'] = $callback_function . '()';
		}

		$callback['details'] = [
			'file' => wp_normalize_path($reflection->getFileName()),
			'line' => $reflection->getStartLine(),
		];

		return $callback;
	}
}
