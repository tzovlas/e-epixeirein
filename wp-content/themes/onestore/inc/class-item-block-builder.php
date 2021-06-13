<?php

function onestore_in_block_item() {
	if ( ! isset( $GLOBALS['onestore_in_block_item'] ) ) {
		$GLOBALS['onestore_in_block_item']  = false;
	}
	return $GLOBALS['onestore_in_block_item'];
}

class OneStore_Item_Block_Builder {
	public $items = [];
	private $data = [];
	private $item_thumb_id = 'thumb';
	private $setting_id = 'thumb';
	private $hook_prefix = 'post';
	private $positions = [
		'top'    => [],
		'thumb_left'  => [],
		'thumb'  => [],
		'thumb_right'  => [],
		'thumb_bottom'  => [],
		'body_before'  => [],
		'body_left'  => [],
		'body'  => [],
		'body_right'  => [],
		'bottom'   => [],
		'bottom_left'   => [],
		'bottom_right'   => [],
	];

	public function __construct( $setting_id = 'woocommerce_index_item_elements' ) {
		$this->setting_id = $setting_id;
	}

	private function render_item( $item_id ) {
		ob_start();
		$cb = 'onestore_item_block_' . $item_id;
		if ( is_callable( $cb ) ) {
			$hook_prefix = 'onestore/item_builder/';
			do_action( $hook_prefix . 'before_item_' . $item_id );
			call_user_func_array( $cb, array( $this ) );
			do_action( $hook_prefix . 'after_item_' . $item_id );
		}
		return ob_get_clean();
	}

	private function render_items( $position, $items, $wrapper_if_empty = false ) {
		$n = count( $items );
		if ( $n < 1 && ! $wrapper_if_empty ) {
			return;
		}

		$classes = 'item-builder--' . $position;
		if ( 1 == $n ) {
			$classes .= ' has-one';
		} else {
			$classes .= ' has-more ni-' . $n;
		}

		echo '<div class="' . esc_attr( $classes ) . '">';
		foreach ( $items as $k ) {
			$item_content = $this->render_item( $k );
			if ( $item_content ) {
				$item_id = 'item-' . str_replace( '_', '-', $k );
				echo '<div class="item-builder--el ' . esc_attr( $item_id ) . '">' . $item_content . '</div>'; // WPCS: XSS ok.
			}
		}

		echo '</div>';
	}

	private function get_items( $position ) {
		if ( isset( $this->data[ $position ] ) ) {
			return $this->data[ $position ];
		}
		return array();
	}

	public function get_data( $setting = false ) {
		if ( ! $setting ) {
			$setting = $this->setting_id;
		}
		$this->data = array();
		foreach ( $this->positions as $key => $default_items ) {
			$items = onestore_get_theme_mod( $setting . '_' . $key );
			if ( ! is_array( $items ) ) {
				$items  = array();
			}
			$this->data[ $key ] = $items;
		}
		return $this->data;
	}

	public function set_thumb_id( $item_id ) {
		$this->item_thumb_id = $item_id;
	}

	public function set_hook_prefix( $prefix ) {
		$this->hook_prefix = $prefix;
	}

	public function render() {
		$GLOBALS['onestore_in_block_item'] = true;
		$this->get_data();
		$body_items = $this->get_items( 'body' );
		$body_left_items = $this->get_items( 'body_left' );
		$body_right_items = $this->get_items( 'body_right' );
		$has_body_center = ! empty( $body_items );

		$bottom_items = $this->get_items( 'bottom' );
		$bottom_left_items = $this->get_items( 'bottom_left' );
		$bottom_right_items = $this->get_items( 'bottom_right' );
		$has_bottom_center = ! empty( $bottom_items );

		$count = 0;
		$body_class = [ 'item-builder--body item-builder--r' ];
		if ( $has_body_center ) {
			$body_class['center'] = 'has-center';
			$count ++;
		}

		if ( ! empty( $body_left_items ) ) {
			$body_class['left'] = 'has-left';
			$count ++;
		}

		if ( ! empty( $body_right_items ) ) {
			$body_class['right'] = 'has-right';
			$count ++;
		}

		if ( 1 == $count ) {
			$body_class[] = 'has-one';
		}

		$bottom_count = 0;
		$bottom_class = [ 'item-builder--bottom item-builder--r' ];
		if ( $has_bottom_center ) {
			$bottom_class['center'] = 'has-center';
			$bottom_count ++;
		}

		if ( ! empty( $bottom_left_items ) ) {
			$bottom_class['left'] = 'has-left';
			$bottom_count ++;
		}

		if ( ! empty( $bottom_right_items ) ) {
			$bottom_class['right'] = 'has-right';
			$bottom_count ++;
		}

		if ( 1 == $bottom_count ) {
			$bottom_class[] = 'has-one';
		}

		do_action( 'onestore/item_builder/start' );

		$hook_prefix = 'onestore/item_builder/' . $this->hook_prefix;
		?>
		<div class="item-builder">
			<?php $this->render_items( 'top', $this->get_items( 'top' ) ); ?>
			<div class="item-builder--thumb">
				<?php do_action( $hook_prefix . '/thumb_before' ); ?>
				<div class="item-builder--thumb-img">
					<?php echo $this->render_item( $this->item_thumb_id ); // WPCS: XSS ok. ?>
				</div>
				<?php $this->render_items( 'thumb-left', $this->get_items( 'thumb_left' ) ); ?>
				<?php $this->render_items( 'thumb-center', $this->get_items( 'thumb' ) ); ?>
				<?php $this->render_items( 'thumb-bottom', $this->get_items( 'thumb_bottom' ) ); ?>
				<?php $this->render_items( 'thumb-right', $this->get_items( 'thumb_right' ) ); ?>
				<?php do_action( $hook_prefix . '/thumb_after' ); ?>
			</div>
			<?php $this->render_items( 'body-before', $this->get_items( 'body_before' ) ); ?>
			<?php if ( ! empty( $body_items ) || ! empty( $body_left_items ) || ! empty( $body_right_items ) ) { ?>
			<div class="<?php echo esc_attr( join( ' ', $body_class ) ); ?>">
				<?php $this->render_items( 1 != $count ? 'r-left' : 'body-main', $body_left_items, $has_body_center ); ?>
				<?php $this->render_items( 1 != $count ? 'r-center' : 'body-main', $body_items ); ?>
				<?php $this->render_items( 1 != $count ? 'r-right' : 'body-main', $body_right_items, $has_body_center ); ?>
			</div>
			<?php } ?>
			<?php if ( ! empty( $bottom_items ) || ! empty( $bottom_left_items ) || ! empty( $bottom_right_items ) ) { ?>
			<div class="<?php echo esc_attr( join( ' ', $bottom_class ) ); ?>">
				<?php $this->render_items( 1 != $bottom_count ? 'r-left' : 'bottom', $bottom_left_items, $has_bottom_center ); ?>
				<?php $this->render_items( 1 != $bottom_count ? 'r-center' : 'bottom', $bottom_items ); ?>
				<?php $this->render_items( 1 != $bottom_count ? 'r-right' : 'bottom', $bottom_right_items, $has_bottom_center ); ?>
			</div>
			<?php } ?>

		</div>
		<?php
		do_action( 'onestore/item_builder/end' );
		$GLOBALS['onestore_in_block_item'] = false;
	}




}
