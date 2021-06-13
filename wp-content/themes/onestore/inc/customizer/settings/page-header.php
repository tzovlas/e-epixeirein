<?php
OneStore_Customizer::set_wp_customize();
OneStore_Customizer::no_panel();

OneStore_Customizer::section_start( 'onestore_section_page_header' );
OneStore_Customizer::set_current_priority( 20 );


OneStore_Customizer::add_noti(
	'page_header_noti',
	'<div class="notice notice-info notice-alt inline"><p>' . sprintf(
		/* translators: %1$s: section name, %2$s: link to Dynamic Page Settings. */
		esc_html__( 'You can set different %1$s setting on each page using the %2$s.', 'onestore' ),
		esc_html__( 'Page Header', 'onestore' ),
		'<a href="' . esc_url( add_query_arg( 'autofocus[panel]', 'onestore_panel_page_settings', remove_query_arg( 'autofocus' ) ) ) . '" class="onestore-customize-goto-control">' . esc_html__( 'Dynamic Page Settings', 'onestore' ) . '</a>'
	) . '</p></div>'
);

OneStore_Customizer::add_field(
	'page_header',
	array(
		'control_class' => 'toggle',
		'control' => array(
			'label'       => esc_html__( 'Enable Page Header', 'onestore' ),
		),
	)
);

$settings = array(
	'left'    => 'page_header_elements_left',
	'center'  => 'page_header_elements_center',
	'right'   => 'page_header_elements_right',
);

OneStore_Customizer::add_field(
	'page_header_elements',
	array(
		'control_class' => 'builder',
		'control' => array(
			'settings'    => $settings,
			'label'       => esc_html__( 'Page header elements', 'onestore' ),
			'choices'     => array(
				'title'      => esc_html__( 'Post / Page Title', 'onestore' ),
				'breadcrumb' => esc_html__( 'Breadcrumb', 'onestore' ),
			),
			'labels'      => array(
				'left'    => is_rtl() ? esc_html__( 'Right', 'onestore' ) : esc_html__( 'Left', 'onestore' ),
				'center'  => esc_html__( 'Center', 'onestore' ),
				'right'   => is_rtl() ? esc_html__( 'Left', 'onestore' ) : esc_html__( 'Right', 'onestore' ),
			),
			'layout'      => 'block',
		),
		'refresh' => array(
			'selector' => '#page-header',
			'render_callback' => 'onestore_page_header',
		),

	)
);

OneStore_Customizer::add_noti(
	'page_header_noti',
	esc_html__( 'Post / Page Title', 'onestore' ) . '<br/>' .
	sprintf(
		/* translators: %s: link to Dynamic Page Settings. */
		esc_html__( 'Show the title of current page, whether it\'s a static page, a single post page, or an archive page. You can change the title text format for search results and archive pages via %s.', 'onestore' ),
		'<a href="' . esc_url( add_query_arg( 'autofocus[panel]', 'onestore_panel_page_settings', remove_query_arg( 'autofocus' ) ) ) . '" class="onestore-customize-goto-control">' . esc_html__( 'Dynamic Page Settings', 'onestore' ) . '</a>'
	)
);

OneStore_Customizer::add_field(
	'breadcrumb_plugin',
	array(
		'control' => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Breadcrumb', 'onestore' ),
			'description' => esc_html__( 'To enable breadcrumb feature, you need to install one of the following plugins and enable the breadcrumb feature on the plugin\'s settings page.', 'onestore' ),
			'choices'     => array(
				'default'        => esc_html__( 'Default', 'onestore' ),
				'rank-math'        => esc_html__( 'Rank Math', 'onestore' ),
				'seopress'         => esc_html__( 'SEOPress (pro version)', 'onestore' ),
				'yoast-seo'        => esc_html__( 'Yoast SEO', 'onestore' ),
				'breadcrumb-navxt' => esc_html__( 'Breadcrumb NavXT', 'onestore' ),
				'breadcrumb-trail' => esc_html__( 'Breadcrumb Trail', 'onestore' ),
			),
		),
		'refresh' => array(
			'selector' => '#page-header',
			'render_callback' => 'onestore_page_header',
		),
	)
);


OneStore_Customizer::add_field(
	'page_header_wrapper',
	array(
		'control_class' => 'css',
		'selector' => '#page-header', // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => '', // or array( 'desktop', 'tablet', 'mobile );
		'control' => array(
			'label'      => esc_html__( 'Wrapper Styling', 'onestore' ),
		),
		'css_rules' => array(
			'padding' => array(
				array(
					'element' => '#page-header .page-header-inner',
				),
			),
			'align_items' => array(
				array(
					'element' => '#page-header .page-header-inner',
				),
			),
			'justify_content' => array(
				array(
					'element' => '#page-header .page-header-inner',
				),
			),
			'max_width' => array(
				array(
					'element' => '#page-header .section-inner',
				),
			),
		),
		'hide_groups' => array(
			'typo',
			'outline',
		),
		'disabled_props' => array(
			'width',
			'min_width',
			'max_height',
			'min_height',
			'display',
			'float',
			'clear',
			'visibility',
		),
	)
);

OneStore_Customizer::add_field(
	'page_header_overlay',
	array(
		'control_class' => 'css',
		'selector' => '#page-header:before', // apply for css tyle only.
		'devices' => false, // or array( 'desktop', 'tablet', 'mobile );
		'states' => '', // or array( 'desktop', 'tablet', 'mobile );
		'control' => array(
			'label'      => esc_html__( 'Wrapper Overlay', 'onestore' ),
		),
		'hide_groups' => array(
			'typo',
			'outline',
			'shadow',
			'border',
			'extra',
			'sizing',
			'spacing',
		),
	)
);

OneStore_Customizer::add_field(
	'page_header_title',
	array(
		'control_class' => 'css',
		'selector' => '#page-header .page-header-title', // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => '', // or array( 'desktop', 'tablet', 'mobile );
		'control' => array(
			'label'      => esc_html__( 'Title Styling', 'onestore' ),
		),
		'hide_groups' => array(
			'outline',
			'border',
			'background',
			'sizing',
			'extra',
		),
		'disabled_props' => array(
			'width',
			'min_width',
			'max_height',
			'min_height',
			'max_width',
			'display',
			'float',
			'clear',
			'visibility',
		),
	)
);

OneStore_Customizer::add_field(
	'page_header_text',
	array(
		'control_class' => 'css',
		'selector' => '#page-header .page-header-inner, #page-header .page-header-inner a, #page-header .page-header-inner a:hover', // apply for css tyle only.
		'devices' => false, 
		'states' => '',
		'control' => array(
			'label'      => esc_html__( 'Text Styling', 'onestore' ),
		),
		'hide_groups' => array(
			'outline',
			'border',
			'background',
			'sizing',
			'extra',
		),
		'disabled_props' => array(
			'width',
			'min_width',
			'max_height',
			'min_height',
			'max_width',
			'display',
			'float',
			'clear',
			'visibility',
		),
	)
);




OneStore_Customizer::section_end();
OneStore_Customizer::no_panel();
