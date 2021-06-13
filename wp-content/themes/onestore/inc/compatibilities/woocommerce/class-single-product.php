<?php
class OneStore_Single_Product {
	protected $setting_key = 'wc_single_layout_items';
	protected $layout_items = array();
	public function __construct() {
		$postions = array(
			'top' => array(),
			'main_left' => array(),
			'main_right' => array(),
			'bottom' => array(),
		);
		foreach ( $postions as $id => $defaults ) {
			$this->layout_items[ $id ] = onestore_get_theme_mod( $this->setting_key . '_' . $id );
			if ( ! $this->layout_items[ $id ] || empty( $this->layout_items[ $id ] ) ) {
				$this->layout_items[ $id ] = $defaults;
			}
		}

	}

	public function render() {
		$top = $this->render_items( $this->layout_items['top'] );
		$main_left = $this->render_items( $this->layout_items['main_left'] );
		$main_right = $this->render_items( $this->layout_items['main_right'] );
		$bottom = $this->render_items( $this->layout_items['bottom'] );

		if ( $top ) {
			echo '<div class="device-columns single-product-section product-section-top">';
			echo '<div class="single-col col col-full">';
			echo $top; // WPCS: XSS ok.
			echo '</div>';
			echo '</div>';
		}
		if ( $main_left || $main_right ) {
			echo '<div data-desktop="2" dat-tablet="2" data-mobile="1" class="device-columns single-product-section product-section-main">';
			if ( $main_left ) {
				echo '<div class="single-col col col-left">';
				echo $main_left; // WPCS: XSS ok.
				echo '</div>';
			}
			if ( $main_right ) {
				echo '<div class="single-col col col-right">';
				echo $main_right; // WPCS: XSS ok.
				echo '</div>';
			}
			echo '</div>';
		}

		do_action( 'woocommerce_single_product_summary' );

		if ( $bottom ) {
			echo '<div class="device-columns single-product-section product-section-bottom">';
			echo '<div class="single-col col col-full">';
			echo $bottom; // WPCS: XSS ok.
			echo '</div>';
			echo '</div>';
		}
	}

	protected function render_items( $items ) {
		if ( ! is_array( $items ) || empty( $items ) ) {
			return '';
		}
		ob_start();
		foreach ( $items as $item_id ) {

			$cb = apply_filters( 'onestore/wc/single_render_item', false, $item_id );
			if ( ! $cb ) {
				if ( method_exists( $this, 'item__' . $item_id ) ) {
					$cb = array( $this, 'item__' . $item_id );
				}
			}

			if ( $cb && is_callable( $cb ) ) {
				call_user_func_array( $cb, array() );
			}
		}

		return ob_get_clean();

	}

	protected function item__gallery() {
		echo '<div class="product-media entry-media">';
		woocommerce_show_product_sale_flash();
		woocommerce_show_product_images();
		echo '</div>';
	}

	protected function item__breadcrumb() {
		woocommerce_breadcrumb();
	}

	protected function item__sale() {
		woocommerce_show_product_sale_flash();
	}

	protected function item__title() {
		woocommerce_template_single_title();
	}

	protected function item__rating() {
		woocommerce_template_single_rating();
	}
	protected function item__cats() {
		global $product;
		echo wc_get_product_category_list( $product->get_id(), ', ', '<div class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'onestore' ) . ' ', '</div>' );
	}
	protected function item__tags() {
		global $product;
		echo wc_get_product_tag_list( $product->get_id(), ', ', '<div class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'onestore' ) . ' ', '</div>' );
	}

	protected function item__description() {
		global $product;
		if ( $product ) {
			echo '<div class="product-description">';
			the_content();
			echo '</div>';
		}
	}

	protected function item__price() {
		woocommerce_template_single_price();
	}

	protected function item__sku() {
		global $product;
		if ( $product ) {
			if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) :
				?>
			<div class="sku_wrapper"><?php esc_html_e( 'SKU:', 'onestore' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'onestore' ); ?></span></div>
			<?php endif;
		}
	}

	protected function item__excerpt() {
		woocommerce_template_single_excerpt();
	}

	protected function item__form() {
		woocommerce_template_single_add_to_cart();
	}

	protected function item__meta() {
		woocommerce_template_single_meta();
	}

	protected function item__data_sections() {
		$cb = 'woocommerce_output_product_data_tabs';
		if ( onestore_is_plus() ) {
			$cb = apply_filters( 'onestore/single_product/item__data_sections_type', $cb );
		}
		call_user_func_array( $cb, array() );
	}

	protected function item__upsell() {
		woocommerce_upsell_display();
	}

	protected function item__related() {
		woocommerce_output_related_products();
	}

	protected function item__reviews() {
		comments_template();
	}

	protected function item__information() {
		woocommerce_product_additional_information_tab();
	}



}
