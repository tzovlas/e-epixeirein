<?php

OneStore_Customizer::set_wp_customize();
OneStore_Customizer::no_panel();
OneStore_Customizer::section_start( 'onestore_section_layout', esc_html__( 'Layout', 'onestore' ), 172 );
OneStore_Customizer::set_current_priority( 37 );


OneStore_Customizer::add_heading(
	'page_layout_h1',
	__( 'Wrapper', 'onestore' ),
);

OneStore_Customizer::add_field(
	'page_layout',
	array(
		'control_class' => 'radio_image',
		'control' => array(
			'label'       => esc_html__( 'Layout', 'onestore' ),
			'choices'     => array(
				'full-width' => array(
					'label' => esc_html__( 'Full width', 'onestore' ),
					'image' => ONESTORE_IMAGES_URL . '/customizer/page-layout--full-width.svg',
				),
				'boxed'      => array(
					'label' => esc_html__( 'Boxed', 'onestore' ),
					'image' => ONESTORE_IMAGES_URL . '/customizer/page-layout--boxed.svg',
				),
			),
		),
		'css_live' => array(
			array(
				'type'    => 'class',
				'element' => 'body',
				'pattern' => 'page-layout-$',
			),
		),
	)
);


OneStore_Customizer::add_field(
	'boxed_page',
	array(
		'control_class' => 'css',
		'selector' => '.page-layout-boxed #page', // apply for css tyle only.
		'devices' => 'all', // all or array( 'desktop', 'tablet', 'mobile );
		'states' => false,
		'control' => array(
			'label'      => esc_html__( 'Boxed Wrapper', 'onestore' ),
		),
		'hide_groups' => array(
			'typo',
			'extra',
			'outline',
		),
		'disabled_props' => array(
			'height',
			'min_height',
			'max_height',
		),
		'condition' => array(
			array(
				'setting'  => 'page_layout',
				'value'    => 'boxed',
			),
		),
	)
);

OneStore_Customizer::add_field(
	'boxed_page_inner',
	array(
		'control_class' => 'css',
		'selector' => '.page-layout-boxed .section-inner', // apply for css tyle only.
		'devices' => 'all', // all or array( 'desktop', 'tablet', 'mobile );
		'states' => false,
		'control' => array(
			'label'      => esc_html__( 'Boxed Inner', 'onestore' ),
			'description'      => esc_html__( 'Apply for section inner.', 'onestore' ),
		),
		'hide_groups' => array(
			'typo',
			'extra',
			'background',
			'border',
			'outline',
			'shadow',
			'sizing',
		),
		'disabled_props' => array(
			'margin',
			'height',
			'min_height',
			'max_height',
		),
		'condition' => array(
			array(
				'setting'  => 'page_layout',
				'value'    => 'boxed',
			),
		),
	)
);


OneStore_Customizer::add_field(
	'full_width_section',
	array(
		'control_class' => 'css',
		'selector' => '.page-layout-full-width .section-inner', // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => '', // or array( 'desktop', 'tablet', 'mobile );
		'control' => array(
			'label'      => esc_html__( 'Full Width', 'onestore' ),
		),
		'hide_groups' => array(
			'typo',
			'border',
			'shadow',
			'outline',
			'background',
			'extra',
		),
		'disabled_props' => array(
			'height',
			'min_height',
			'max_height',
			'width',
			'min_width',
		),
		'css_rules' => array(
			'width'      => array(
				array(
					'element' => 'body.page-layout-full-width .wrapper',
				),
			),
			'min_width'      => array(
				array(
					'element' => 'body.page-layout-full-width .wrapper',
				),
			),
			'max_width'      => array(
				array(
					'element' => 'body.page-layout-full-width .wrapper',
				),
			),
		),
		'condition' => array(
			array(
				'setting'  => 'page_layout',
				'value'    => 'full-width',
			),
		),
	)
);

OneStore_Customizer::add_heading(
	'page_layout_heading_main',
	__( 'Main (Content & Sidebar)', 'onestore' ),
);

OneStore_Customizer::add_noti(
	'page_layout_main_noti',
	esc_html__( 'Main contains sidebar and content.', 'onestore' ),
);

$message = '<div class="notice notice-info notice-alt inline"><p>' . sprintf(
	/* translators: %1$s: section name, %2$s: link to Dynamic Page Settings. */
	esc_html__( 'You can set different %1$s setting on each page using the %2$s.', 'onestore' ),
	esc_html__( 'Content Section', 'onestore' ),
	'<a href="' . esc_url( add_query_arg( 'autofocus[panel]', 'onestore_panel_page_settings', remove_query_arg( 'autofocus' ) ) ) . '" class="onestore-customize-goto-control">' . esc_html__( 'Dynamic Page Settings', 'onestore' ) . '</a>'
) . '</p></div>';
OneStore_Customizer::add_noti( 'sidebar_notice', $message );


OneStore_Customizer::add_field(
	'content_layout',
	array(
		'control_class' => 'radio_image',
		'control' => array(
			'label'       => esc_html__( 'Content & sidebar layout', 'onestore' ),
			'choices'     => array(
				'wide'          => array(
					'label' => esc_html__( 'No Sidebar', 'onestore' ),
					'image' => ONESTORE_IMAGES_URL . '/customizer/content-sidebar-layout--wide.svg',
				),
				'left-sidebar'  => array(
					'label' => is_rtl() ? esc_html__( 'Right sidebar', 'onestore' ) : esc_html__( 'Left sidebar', 'onestore' ),
					'image' => ONESTORE_IMAGES_URL . '/customizer/content-sidebar-layout--left-sidebar.svg',
				),
				'right-sidebar' => array(
					'label' => is_rtl() ? esc_html__( 'Left sidebar', 'onestore' ) : esc_html__( 'Right sidebar', 'onestore' ),
					'image' => ONESTORE_IMAGES_URL . '/customizer/content-sidebar-layout--right-sidebar.svg',
				),
			),
		),

	)
);


OneStore_Customizer::add_field(
	'content_main',
	array(
		'control_class' => 'css',
		'selector' => '#content', // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => '', // or array( 'desktop', 'tablet', 'mobile );
		'control' => array(
			'label'      => esc_html__( 'Main Settings', 'onestore' ),

		),
		'hide_groups' => array(
			'typo',
			'extra',
			'border',
			'outline',
			'shadow',
		),
		'disabled_props' => array(
			'height',
			'min_height',
			'max_height',
			'with',
			'min_width',
		),

		'css_rules' => array(
			'max_width'      => array(
				array(
					'element' => '#content .section-inner',
				),

			),

		),
	)
);

OneStore_Customizer::add_heading(
	'page_layout_heading_content',
	__( 'Content Area', 'onestore' ),
);

OneStore_Customizer::add_field(
	'content_area',
	array(
		'control_class' => 'css',
		'selector' => '#main', // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => '', // or array( 'desktop', 'tablet', 'mobile );
		'control' => array(
			'label'      => esc_html__( 'Content Settings', 'onestore' ),

		),
		'hide_groups' => array(
			'typo',
			'extra',
			'background',
			'outline',
			'border',
			'shadow',
		),
		'disabled_props' => array(
			'height',
			'min_height',
			'max_height',
			'width',
			'min_width',
		),

	)
);


OneStore_Customizer::add_heading(
	'page_layout_heading_sidebar',
	__( 'Sidebar Area', 'onestore' ),
);

OneStore_Customizer::add_field(
	'sidebar',
	array(
		'control_class' => 'css',
		'selector' => '.sidebar-inner', // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => '', // or array( 'desktop', 'tablet', 'mobile );
		'control' => array(
			'label'      => esc_html__( 'Sidebar Settings', 'onestore' ),
		),
		'css_rules' => array(
			'width'      => array(
				array(
					'element' => '#primary',
					'property' => 'flex-basis',
					'pattern'  => 'calc(100% - $)',
				),
				array(
					'element' => '.sidebar',
					'property' => 'flex-basis',
				),
			),
			'min_width'      => array(
				array(
					'element' => '#primary',
					'property' => 'min-width',
					'pattern'  => 'calc(100% - $)',
				),
				array(
					'element' => '.sidebar',
					'property' => 'min-width',
				),
			),
		),

		'hide_groups' => array(
			'typo',
			'outline',
			'extra',
			'sizing',
		),
		'disabled_props' => array(
			'height',
			'min_height',
			'max_height',
		),
	)
);

OneStore_Customizer::add_field(
	'sidebar_widget',
	array(
		'control_class' => 'css',
		'selector' => '.sidebar .widget', // apply for css tyle only.
		'selector' => array(
			'default' => '.sidebar .widget', // apply for css tyle only.
			'heading' => '.sidebar .widget .widget-title', // apply for css tyle only.
		),
		'devices' => 'all', // all or array( 'desktop', 'tablet', 'mobile );
		'states' => array(
			'default' => esc_html__( 'Widget Content', 'onestore' ),
			'heading' => esc_html__( 'Widget Title', 'onestore' ),
		), // or array( 'desktop', 'tablet', 'mobile );
		'control' => array(
			'label'      => esc_html__( 'Widgets Settings', 'onestore' ),
		),
		'hide_groups' => array(
			'typo',
			'outline',
			'extra',
		),
		'disabled_props' => array(
			'width',
			'min_width',
			'max_width',
		),
	)
);

OneStore_Customizer::add_field(
	'sidebar_content_gap',
	array(
		'control_class' => 'slider',
		'devices' => 'all',
		'control' => array(
			'label'      => esc_html__( 'Sidebar Content Gap', 'onestore' ),
			'units'       => array(
				'px' => array(
					'min'   => 0,
					'max'   => 150,
					'step'  => 1,
				),
				'em' => array(
					'min'   => 0,
					'max'   => 15,
					'step'  => 0.1,
				),
			),
		),
		'css_live' => array(
			array(
				'type'     => 'css',
				'element'  => '.content-layout-right-sidebar .sidebar',
				'property' => 'padding-left',
			),
			array(
				'type'     => 'css',
				'element'  => '.content-layout-left-sidebar .sidebar',
				'property' => 'padding-right',
			),

		),
	)
);

OneStore_Customizer::section_end();
