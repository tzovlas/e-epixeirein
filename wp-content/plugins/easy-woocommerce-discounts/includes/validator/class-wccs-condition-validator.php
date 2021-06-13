<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WCCS_Condition_Validator {

	protected $customer;

	protected $products;

	protected $cart;

	public function __construct(
		$customer = null,
		WCCS_Products $products = null,
		WCCS_Cart $cart = null
	) {
		$wccs            = WCCS();
		$this->customer  = ! is_null( $customer ) ? new WCCS_Customer( $customer ) : new WCCS_Customer( wp_get_current_user() );
		$this->products  = ! is_null( $products ) ? $products : $wccs->products;
		$this->cart      = ! is_null( $cart ) ? $cart : $wccs->cart;
	}

	public function is_valid_conditions( array $conditions, $match_mode = 'all' ) {
		if ( empty( $conditions ) ) {
			return true;
		}

		foreach ( $conditions as $condition ) {
			if ( 'one' === $match_mode && $this->is_valid( $condition ) ) {
				return true;
			} elseif ( 'all' === $match_mode && ! $this->is_valid( $condition ) ) {
				return false;
			}
		}

		return 'all' === $match_mode;
	}

	public function is_valid( array $condition ) {
		if ( empty( $condition ) ) {
			return false;
		}

		$is_valid = false;
		if ( method_exists( $this, $condition['condition'] ) ) {
			$is_valid = $this->{$condition['condition']}( $condition );
		}

		return apply_filters( 'wccs_condition_validator_is_valid', $is_valid, $condition );
	}

	public function number_of_cart_items( array $condition ) {
		$value = ! empty( $condition['number_value_2'] ) ? intval( $condition['number_value_2'] ) : 0;
		if ( $value < 0 ) {
			return false;
		}

		/**
		 * Checking is WooCommerce cart initialized.
		 * Avoid making an issue in WooCommerce API.
		 */
		if ( ! WC()->cart ) {
			return false;
		}

		return WCCS()->WCCS_Comparison->math_compare( $this->cart->get_cart_contents_count(), $value, $condition['math_operation_type'] );
	}

	public function subtotal_including_tax( array $condition ) {
		$value = ! empty( $condition['number_value_2'] ) ? floatval( $condition['number_value_2'] ) : 0;
		if ( $value < 0 ) {
			return false;
		}

		/**
		 * Checking is WooCommerce cart initialized.
		 * Avoid making an issue in WooCommerce API.
		 */
		if ( ! WC()->cart ) {
			return false;
		}

		return WCCS()->WCCS_Comparison->math_compare( $this->cart->subtotal, $value, $condition['math_operation_type'] );
	}

	public function cart_total_weight( array $condition ) {
		$value = ! empty( $condition['number_value_2'] ) ? intval( $condition['number_value_2'] ) : 0;
		if ( $value < 0 ) {
			return false;
		}

		return WCCS()->WCCS_Comparison->math_compare( $this->cart->get_cart_contents_weight(), $value, $condition['math_operation_type'] );
	}

}
