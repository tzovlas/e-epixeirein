<?php
/**
 * Custom Posts Widget.
 *
 * @package OneStore
 */

class OneStore_Widget_Builder_Posts extends WP_Widget {

	function __construct() {
		parent::__construct(
			'onestore_widget_builder_posts',
			/* translators: %s: theme name. */
			esc_html__( 'OneStore: Posts', 'onestore' ),
			array(
				'classname' => 'onestore_widget_builder_posts',
				'description' => esc_html__( 'Display posts, recommended for front page or page builder.', 'onestore' ),
				'customize_selective_refresh' => true,
			)
		);
	}

	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$cats = get_categories();
		$category_default = array();
		foreach ( $cats as $cat ) {
			$category_default[] = $cat->term_id;
		}

		$title          = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) : '';
		$number         = isset( $instance['number'] ) ? absint( $instance['number'] ) : 4;
		$columns         = isset( $instance['columns'] ) ? sanitize_text_field( $instance['columns'] ) : '2-2-1';
		$orderby        = isset( $instance['orderby'] ) ? sanitize_key( $instance['orderby'] ) : 'post_date';
		$all_categories = isset( $instance['all_categories'] ) ? (bool) $instance['all_categories'] : false;
		$category       = isset( $instance['category'] ) ? $instance['category'] : $category_default;
		$show_date      = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;

		$query_args = array(
			'post_type'           => 'post',
			'posts_per_page'      => $number,
			'ignore_sticky_posts' => 1,
			'orderby'             => $orderby,
		);
		if ( ! $all_categories ) {
			$query_args['cat'] = implode( ',', $category );
		}

		$query = new WP_Query( $query_args );

		if ( $query->have_posts() ) :

			echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$settings = onestore_string_to_devices( $columns );

			?>
			<div <?php onestore_array_to_html_attributes( $settings ); ?> class="loop-posts device-columns">
				<?php
				// Start the loop.
				while ( $query->have_posts() ) :
					$query->the_post();
					// Render post content using selected layout on Customizer.
					onestore_get_template_part( 'post' );
				endwhile;
				?>
			</div>
			<?php
			wp_reset_postdata();
			echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		endif;
	}

	public function form( $instance ) {
		$cats = get_categories();

		$orders = array(
			'post_date' => esc_html__( 'Recent Published', 'onestore' ),
			'rand'      => esc_html__( 'Random', 'onestore' ),
		);

		$title          = isset( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : '';
		$number         = isset( $instance['number'] ) ? absint( $instance['number'] ) : 4;
		$columns         = isset( $instance['columns'] ) ? sanitize_text_field( $instance['number'] ) : '2-2-1';
		$orderby        = isset( $instance['orderby'] ) ? sanitize_key( $instance['orderby'] ) : 'post_date';
		$all_categories = isset( $instance['all_categories'] ) ? (bool) $instance['all_categories'] : false;
		$category       = isset( $instance['category'] ) ? $instance['category'] : array();
		$show_date      = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'onestore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'Columns:', 'onestore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" type="text" value="<?php echo esc_attr( $columns ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'onestore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Sort By:', 'onestore' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
				<?php foreach ( $orders as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $orderby, $key ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'all_categories' ) ); ?>">
				<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'all_categories' ) ); ?>" type="checkbox" <?php checked( $all_categories ); ?> name="<?php echo esc_attr( $this->get_field_name( 'all_categories' ) ); ?>">
				<?php esc_html_e( 'Query from All Categories?', 'onestore' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'or Choose Specific Categories:', 'onestore' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>[]" multiple>
				<?php foreach ( $cats as $cat ) : ?>
					<option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php echo ( in_array( $cat->term_id, (array) $category ) ? 'selected' : '' ); ?>><?php echo esc_html( $cat->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance                   = $old_instance;

		$instance['title']          = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['number']         = isset( $new_instance['number'] ) ? absint( $new_instance['number'] ) : 5;
		$instance['columns']         = isset( $new_instance['columns'] ) ? sanitize_text_field( $new_instance['columns'] ) : '3-3-1';
		$instance['orderby']        = isset( $new_instance['orderby'] ) ? sanitize_key( $new_instance['orderby'] ) : 'post_date';
		$instance['all_categories'] = isset( $new_instance['all_categories'] ) ? (bool) $new_instance['all_categories'] : false;
		$instance['category']       = isset( $new_instance['category'] ) ? $new_instance['category'] : array();
		$instance['show_date']      = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;

		return $instance;
	}
}

register_widget( 'OneStore_Widget_Builder_Posts' );
