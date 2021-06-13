<?php
/**
 * Trait ShippingMethodTrait
 *
 * @package WPDesk\FS\TableRate
 */

namespace WPDesk\FS\TableRate;

/**
 * Common methods for shipping methods.
 */
trait ShippingMethodTrait {
	/**
	 * @param array $args .
	 *
	 * @return array
	 */
	private function set_zero_cost_if_negative( $args = array() ) {
		$allow_negative_costs = (bool) apply_filters( 'flexible-shipping/shipping-method/allow-negative-costs', false );

		if ( ! $allow_negative_costs && isset( $args['cost'] ) && 0.0 > (float) $args['cost'] ) {
			$args['cost'] = 0.0;
		}

		return $args;
	}
}
