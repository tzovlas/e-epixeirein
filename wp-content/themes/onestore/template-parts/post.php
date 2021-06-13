<div <?php post_class( 'col post-item' ); ?>>
<?php
	do_action( 'onestore/post_before_shop_loop_item' );
	$item_builder = new OneStore_Item_Block_Builder( 'posts_index_item_elements' );
	$item_builder->set_thumb_id( 'post_thumb' );
	$item_builder->render();
	do_action( 'onestore/post_after_shop_loop_item' );
?>
</div>
