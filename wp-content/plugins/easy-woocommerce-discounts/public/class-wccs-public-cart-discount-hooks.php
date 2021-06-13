<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WCCS_Public_Cart_Discount_Hooks {

	protected $display_multiple;

	const COUPON_ID = 9999999;

	public function __construct( WCCS_Loader $loader ) {
		$this->display_multiple = WCCS()->settings->get_setting( 'cart_discount_display_multiple_discounts', 'separate' );

		$loader->add_action( 'woocommerce_after_calculate_totals', $this, 'add_discount', 20 );
		$loader->add_filter( 'woocommerce_get_shop_coupon_data', $this, 'get_coupon_data', 10, 2 );
		$loader->add_filter( 'woocommerce_cart_totals_coupon_html', $this, 'cart_totals_coupon_html', 10, 2 );
		$loader->add_filter( 'woocommerce_cart_totals_coupon_label', $this, 'cart_totals_coupon_label', 10, 2 );
		$loader->add_action( 'woocommerce_check_cart_items', $this, 'maybe_remove_coupon', 1 );
		$loader->add_filter( 'woocommerce_coupon_message', $this, 'maybe_remove_coupon_message', 99, 3 );
		$loader->add_filter( 'woocommerce_apply_individual_use_coupon', $this, 'apply_individual_use_coupon', 10, 3 );
		$loader->add_filter( 'woocommerce_coupon_is_valid_for_product', $this, 'is_valid_for_product', 99, 4 );
		$loader->add_filter( 'woocommerce_coupon_is_valid_for_cart', $this, 'is_valid_for_cart', 99, 2 );
	}

	public function add_discount() {
		if ( ! WCCS()->cart_discount ) {
			return;
		}

		$with_individuals = WCCS()->settings->get_setting( 'cart_discount_with_individual_coupons', 1 );
		$with_regulars    = WCCS()->settings->get_setting( 'cart_discount_with_regular_coupons', 1 );
		$add_discounts    = true;
		if ( ( 0 == $with_individuals || 0 == $with_regulars ) && ! empty( WC()->cart->applied_coupons ) ) {
			foreach ( WC()->cart->applied_coupons as $code ) {
				// Checking for do not apply with regular coupons.
				if ( 0 == $with_regulars && ! WCCS()->cart_discount->is_cart_discount_coupon( $code ) ) {
					$add_discounts = false;
				}

				// Checking for do not apply with individual use coupons.
				if ( 0 == $with_individuals ) {
					$coupon = new WC_Coupon( $code );
					if ( $coupon->get_individual_use() ) {
						$add_discounts = false;
					}
				}
			}
		}

		if ( ! $add_discounts ) {
			foreach ( WC()->cart->applied_coupons as $code ) {
				if ( WCCS()->cart_discount->is_cart_discount_coupon( $code ) ) {
					WC()->cart->remove_coupon( $code );
				}
			}

			return;
		}

		$discounts = WCCS()->cart_discount->get_possible_discounts();
		if ( empty( $discounts ) ) {
			return;
		}

		if ( 'combine' === $this->display_multiple ) {
			$coupon_code = WCCS()->cart_discount->get_combine_coupon_code();
			if ( ! WC()->cart->has_discount( $coupon_code ) ) {
				WC()->cart->add_discount( $coupon_code );
			}
		} else {
			foreach ( $discounts as $discount ) {
				if ( 0 < $discount->discount_amount && ! WC()->cart->has_discount( $discount->code ) ) {
					WC()->cart->add_discount( $discount->code );
				}
			}
		}
	}

	public function get_coupon_data( $false, $data ) {
		if ( ! WCCS()->cart_discount || ! WCCS()->cart_discount->is_cart_discount_coupon( $data ) ) {
			return $false;
		}

		$discounts = WCCS()->cart_discount->get_possible_discounts();
		if ( empty( $discounts ) ) {
			// @todo change remove coupon functionality.
			WC()->cart->remove_coupon( $data );
			return $false;
		}

		if ( 'combine' === $this->display_multiple ) {
			$coupon_code = WCCS()->cart_discount->get_combine_coupon_code();
			if ( $data === $coupon_code ) {
				$amount = 0;
				foreach ( $discounts as $discount ) {
					$amount += apply_filters( 'wccs_cart_discount_coupon_amount', $discount->discount_amount, $discount );
				}

				return apply_filters(
					'wccs_cart_discount_get_coupon_data',
					array(
						'id'     => self::COUPON_ID,
						'code'   => $coupon_code,
						'amount' => $amount,
					)
				);
			}
		} else {
			if ( isset( $discounts[ $data ] ) ) {
				return apply_filters(
					'wccs_cart_discount_get_coupon_data',
					array(
						'id'     => self::COUPON_ID,
						'code'   => $discounts[ $data ]->code,
						'amount' => apply_filters( 'wccs_cart_discount_coupon_amount', $discounts[ $data ]->discount_amount, $discounts[ $data ] ),
					)
				);
			}
			// @todo change remove coupon functionality.
			WC()->cart->remove_coupon( $data );
		}

		return $false;
	}

	public function cart_totals_coupon_html( $coupon_html, $coupon ) {
		if ( ! WCCS()->cart_discount ) {
			return $coupon_html;
		}

		$code = WCCS()->WCCS_Helpers->wc_version_check() ? $coupon->get_code() : $coupon->code;
		if ( ! WCCS()->cart_discount->is_cart_discount_coupon( $code ) ) {
			return $coupon_html;
		}

		if ( $amount = WC()->cart->get_coupon_discount_amount( $code, WC()->cart->display_cart_ex_tax ) ) {
			return apply_filters( 'wccs_cart_totals_coupon_html_prefix', '-' ) . wc_price( $amount );
		}

		return $coupon_html;
	}

	public function cart_totals_coupon_label( $label, $coupon ) {
		if ( ! WCCS()->cart_discount ) {
			return $label;
		}

		$code = WCCS()->WCCS_Helpers->wc_version_check() ? $coupon->get_code() : $coupon->code;
		if ( ! WCCS()->cart_discount->is_cart_discount_coupon( $code ) ) {
			return $label;
		}

		if ( 'combine' === $this->display_multiple ) {
			$label = __( 'Discount', 'easy-woocommerce-discounts' );
			if ( (int) WCCS()->settings->get_setting( 'localization_enabled', 1 ) ) {
				$label = WCCS()->settings->get_setting( 'coupon_label', $label );
			}
			$label = apply_filters( 'wccs_cart_totals_coupon_label_combine', $label );
			return $label ? esc_html( $label ) : __( 'Discount', 'easy-woocommerce-discounts' );
		}

		$discounts = WCCS()->cart_discount->get_possible_discounts();
		if ( isset( $discounts[ $code ] ) ) {
			return esc_html( $discounts[ $code ]->name );
		}

		return $label;
	}

	public function maybe_remove_coupon() {
		if ( empty( WC()->cart->applied_coupons ) || ! WCCS()->cart_discount ) {
			return;
		}

		foreach ( WC()->cart->applied_coupons as $coupon_code ) {
			if ( ! WCCS()->cart_discount->is_cart_discount_coupon( $coupon_code ) ) {
				continue;
			}

			$coupon = new WC_Coupon( $coupon_code );
			$amount = WCCS()->WCCS_Helpers->wc_version_check() ? $coupon->get_amount() : $coupon->amount;
			if ( $amount <= 0 ) {
				WC()->cart->remove_coupon( $coupon_code );
			}
		}
	}

	/**
	 * Remove coupon message when it is automatic coupon applied with WooCommerce Conditions.
	 *
	 * @param  string    $msg
	 * @param  integer   $msg_code
	 * @param  WC_Coupon $coupon
	 *
	 * @return string
	 */
	public function maybe_remove_coupon_message( $msg, $msg_code, $coupon ) {
		if ( ! WCCS()->cart_discount ) {
			return $msg;
		}

		$code = WCCS()->WCCS_Helpers->wc_version_check() ? $coupon->get_code() : $coupon->code;
		if ( WCCS()->cart_discount->is_cart_discount_coupon( $code ) ) {
			return '';
		}

		return $msg;
	}

	public function apply_individual_use_coupon( $keep_coupons, $coupon, $applied_coupons ) {
		if ( ! WCCS()->cart_discount ) {
			return $keep_coupons;
		}

		$with_individuals = WCCS()->settings->get_setting( 'cart_discount_with_individual_coupons', 1 );
		if ( 0 == $with_individuals ) {
			return $keep_coupons;
		}

		foreach ( $applied_coupons as $coupon_code ) {
			if ( WCCS()->cart_discount->is_cart_discount_coupon( $coupon_code ) ) {
				$keep_coupons[] = $coupon_code;
			}
		}

		return $keep_coupons;
	}

	public function is_valid_for_product( $valid, $product, $coupon, $cart_item ) {
		if ( ! WCCS()->cart_discount->is_cart_discount_coupon( $coupon->get_code() ) ) {
			return $valid;
		}

		if ( 'combine' === $this->display_multiple ) {
			return false;
		}

		$discounts = WCCS()->cart_discount->get_possible_discounts();
		if ( ! isset( $discounts[ $coupon->get_code() ] ) ) {
			return $valid;
		}

		if ( ! in_array( $discounts[ $coupon->get_code() ]->discount_type, array( 'percentage_discount_per_item', 'price_discount_per_item' ) ) ) {
			return false;
		}

		$product   = $cart_item['data'];
		$variation = (int) $cart_item['variation_id'];
		if ( 0 < $variation ) {
			$product   = (int) $cart_item['product_id'];
			$variation = $cart_item['data'];
		}

		$items         = ! empty( $discounts[ $coupon->get_code() ]->items ) ? $discounts[ $coupon->get_code() ]->items : array( array( 'item' => 'all_products' ) );
		$exclude_items = ! empty( $discounts[ $coupon->get_code() ]->exclude_items ) ?  $discounts[ $coupon->get_code() ]->exclude_items : array();
		if ( WCCS()->WCCS_Product_Validator->is_valid_product( $items, $product, $variation, ( ! empty( $cart_item['variation'] ) ? $cart_item['variation'] : array() ) ) ) {
			if ( empty( $exclude_items ) || ! WCCS()->WCCS_Product_Validator->is_valid_product( $exclude_items, $product, $variation, ( ! empty( $cart_item['variation'] ) ? $cart_item['variation'] : array() ) ) ) {
				return true;
			}
		}
		return false;
	}

	public function is_valid_for_cart( $valid, $coupon ) {
		if ( ! WCCS()->cart_discount->is_cart_discount_coupon( $coupon->get_code() ) ) {
			return $valid;
		}

		if ( 'combine' === $this->display_multiple ) {
			return true;
		}

		$discounts = WCCS()->cart_discount->get_possible_discounts();
		if ( isset( $discounts[ $coupon->get_code() ] ) ) {
			return ! in_array( $discounts[ $coupon->get_code() ]->discount_type, array( 'percentage_discount_per_item', 'price_discount_per_item' ) );
		}

		return $valid;
	}

}
