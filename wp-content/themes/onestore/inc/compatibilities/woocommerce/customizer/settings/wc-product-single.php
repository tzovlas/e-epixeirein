<?php
OneStore_Customizer::set_wp_customize();
OneStore_Customizer::no_panel();
OneStore_Customizer::section_start( 'woocommerce_product_single' );
OneStore_Customizer::set_current_priority( 5 );

$builder_setting_key = 'wc_single_layout_items';
$priority = 10;

$settings = array(
	'top'    => $builder_setting_key . '_top',
	'main_left'    => $builder_setting_key . '_main_left',
	'main_right'  => $builder_setting_key . '_main_right',
	'bottom'  => $builder_setting_key . '_bottom',
);
$builder_items = array(
	'gallery' => esc_html__( 'Gallery', 'onestore' ),
	'title' => esc_html__( 'Title', 'onestore' ),
	'breadcrumb' => esc_html__( 'Breadcrumb', 'onestore' ),
	'meta' => esc_html__( 'Meta', 'onestore' ),
	'excerpt' => esc_html__( 'Excerpt', 'onestore' ),
	'sku' => esc_html__( 'Sku', 'onestore' ),
	'price' => esc_html__( 'Price', 'onestore' ),
	'form' => esc_html__( 'Add to Cart', 'onestore' ),
	'rating' => esc_html__( 'Rating', 'onestore' ),
	'sharing' => esc_html__( 'Sharing', 'onestore' ),
	'cats' => esc_html__( 'Categories', 'onestore' ),
	'tags' => esc_html__( 'Tags', 'onestore' ),
	'upsell' => esc_html__( 'Upsell', 'onestore' ),
	'related' => esc_html__( 'Related', 'onestore' ),
	'description' => esc_html__( 'Description', 'onestore' ),
	'information' => esc_html__( 'Information', 'onestore' ),
	'reviews' => esc_html__( 'Reviews', 'onestore' ),
	'data_sections' => esc_html__( 'Data Tabs/Sections', 'onestore' ),
);


OneStore_Customizer::add_field(
	$builder_setting_key,
	array(
		'control_class' => 'builder',
		'control' => array(
			'settings'    => $settings,
			'label'       => esc_html__( 'Elements & Positions', 'onestore' ),
			'choices'     => $builder_items,
			'locked'      => true,
			'labels'      => array(
				'top'    => esc_html__( 'Top', 'onestore' ),
				'main_left'  => esc_html__( 'Main Left', 'onestore' ),
				'main_right'  => esc_html__( 'Main Right', 'onestore' ),
				'bottom'   => esc_html__( 'Bottom', 'onestore' ),
			),
			'layout'      => 'block',
		),

	)
);

$units = array(
	'px' => array(
		'min'  => 0,
		'max'  => 100,
		'step' => 1,
	),
	'%' => array(
		'min'  => 0,
		'max'  => 10,
		'step' => 1,
	),
);

OneStore_Customizer::add_field(
	'wc_single_row_gap',
	array(
		'control_class' => 'slider',
		'devices' => 'all',
		'control' => array(
			'label'       => esc_html__( 'Section gap', 'onestore' ),
			'units'       => $units,
		),
		'live_css' => array(
			array(
				'type'     => 'css',
				'element'  => '.single-product-section.device-columns',
				'property' => 'margin-bottom',
			),
		),
	)
);

OneStore_Customizer::add_field(
	'wc_single_main_left',
	array(
		'control_class' => 'css',
		'devices' => 'all',
		'selector' => '.single-product-section.device-columns .single-col.col-left',
		'control' => array(
			'label'       => esc_html__( 'Main left column', 'onestore' ),
			'locked'      => true,
		),
		'hide_groups' => array(
			'typo',
			'shadow',
			'border',
			'outline',
			'background',
			'extra',
		),
		'disabled_props' => array(
			'min_width',
			'max_width',
			'max_height',
			'height',
			'min_height'
		)
		
	)
);

OneStore_Customizer::add_field(
	'wc_single_main_right',
	array(
		'control_class' => 'css',
		'selector' => '.single-product-section.device-columns .single-col.col-right',
		'devices' => 'all',
		'control' => array(
			'label'       => esc_html__( 'Main right column', 'onestore' ),
			'locked'      => true,
		),
		'hide_groups' => array(
			'typo',
			'shadow',
			'border',
			'outline',
			'background',
			'extra',
		),
		'disabled_props' => array(
			'min_width',
			'max_width',
			'max_height',
			'height',
			'min_height'
		)
		
	)
);


OneStore_Customizer::add_field(
	'wc_single_items_gap',
	array(
		'control_class' => 'slider',
		'devices' => 'all',
		'control' => array(
			'label'       => esc_html__( 'Items Gap', 'onestore' ),
			'units'       => $units,
		),
		'live_css' => array(
			array(
				'type'     => 'css',
				'element'  => '.single-product-section > .single-col > *',
				'property' => 'margin-bottom',
			),
		),
	)
);

	OneStore_Customizer::set_current_priority( 10 );
	OneStore_Customizer::add_heading( 'wc_single_title', esc_html__( 'Gallery Settings', 'onestore' ) );

	OneStore_Customizer::add_field(
		'woocommerce_single_gallery_zoom',
		array(
			'control_class' => 'toggle',
			'devices' => false,
			'control' => array(
				'label'       => esc_html__( 'Enable zoom', 'onestore' ),
			),
		)
	);

	OneStore_Customizer::add_field(
		'woocommerce_single_gallery_lightbox',
		array(
			'control_class' => 'toggle',
			'devices' => false,
			'control' => array(
				'label'       => esc_html__( 'Enable lightbox', 'onestore' ),
			),
		)
	);


	OneStore_Customizer::add_field(
		'woocommerce_single_buy_now',
		array(
			'control_class' => 'toggle',
			'devices' => false,
			'control' => array(
				'label'       => esc_html__( 'Enable Buy Now Button', 'onestore' ),
			),
		)
	);


	OneStore_Customizer::add_field(
		'woocommerce_single_wishlist',
		array(
			'control_class' => 'toggle',
			'devices' => false,
			'control' => array(
				'label'       => esc_html__( 'Enable Wishlist', 'onestore' ),
			),
		)
	);


	OneStore_Customizer::set_current_priority( 40 );

	OneStore_Customizer::add_heading( 'wc_single_heading_more_section', esc_html__( 'Data Sections', 'onestore' ) );




	OneStore_Customizer::set_current_priority( 60 );
	OneStore_Customizer::add_heading( 'wc_single_heading_more_product', esc_html__( 'More Products', 'onestore' ) );
	OneStore_Customizer::add_noti(
		'wc_single_heading_more_notice',
		esc_html__( 'Apply for related, recent viewed, up-sell, cross-sell products', 'onestore' )
	);


	OneStore_Customizer::add_field(
		'woocommerce_single_more_grid_columns',
		array(
			'devices' => false,
			'control' => array(
				'type' => 'text',
				'label'       => esc_html__( 'Columns', 'onestore' ),
			),
		)
	);

	OneStore_Customizer::add_field(
		'woocommerce_single_more_posts_per_page',
		array(
			'devices' => false,
			'control' => array(
				'type' => 'number',
				'label'       => esc_html__( 'Max products shown', 'onestore' ),
				'description' => esc_html__( '0 = disabled; -1 = show all.', 'onestore' ),
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 12,
					'step' => 1,
				),
			),
		)
	);


	OneStore_Customizer::set_current_priority( 85 );
	OneStore_Customizer::add_heading( 'wc_single_sticky_heading', esc_html__( 'Sticky add to cart', 'onestore' ), );

	OneStore_Customizer::add_field(
		'woocommerce_single_sticky_cart',
		array(
			'devices' => false,
			'control' => array(
				'label'       => esc_html__( 'Sticky add to cart', 'onestore' ),
				'type'    => 'select',
				'choices' => array(
					'top' => esc_html__( 'Top', 'onestore' ),
					'bottom' => esc_html__( 'Bottom', 'onestore' ),
					'none' => esc_html__( 'Hide ', 'onestore' ),
				),
			),
		)
	);



	OneStore_Customizer::section_end();
