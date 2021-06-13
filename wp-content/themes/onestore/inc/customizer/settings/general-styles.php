<?php

OneStore_Customizer::set_wp_customize();
OneStore_Customizer::no_panel();
OneStore_Customizer::section_start( 'onestore_section_general_styles', esc_html__( 'General Settings', 'onestore' ), 171 );
OneStore_Customizer::set_current_priority( 37 );

OneStore_Customizer::add_field(
	'body',
	array(
		'control_class' => 'css',
		'selector' => 'body', // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'control' => array(
			'label'      => esc_html__( 'Body', 'onestore' ),
			'description' => esc_html__( 'The global settings of body typography and styling.', 'onestore' ),
		),
	)
);

OneStore_Customizer::add_field(
	'font_smoothing',
	array(
		'control_class' => 'toggle',
		'control' => array(
			'label'       => esc_html__( 'Enable font smoothing', 'onestore' ),
			'description' => esc_html__( 'For better font rendering on OSX browsers (recommended).', 'onestore' ),
		),
		'css_live' => array(
			array(
				'type'     => 'class',
				'element'  => 'body',
				'pattern'  => 'onestore-font-smoothing-$',
			),
		),
	)
);

OneStore_Customizer::add_field(
	'link',
	array(
		'control_class' => 'css',
		'selector' => array(
			'default' => '
			a, .action-toggle,
			.navigation .nav-links a:hover, .navigation .nav-links a:focus, .tagcloud a:hover, .tagcloud a:focus, .reply:hover, .reply:focus,
			.entry-meta a:hover, .entry-meta a:focus, .comment-metadata a:hover, .comment-metadata a:focus, .widget .post-date a:hover, .widget .post-date a:focus, .widget_rss .rss-date a:hover, .widget_rss .rss-date a:focus,
			h1 a:hover, h1 a:focus, .h1 a:hover, .h1 a:focus, h2 a:hover, h2 a:focus, 
			.h2 a:hover, .h2 a:focus, h3 a:hover, h3 a:focus, .h3 a:hover, .h3 a:focus, h4 a:hover, h4 a:focus, .h4 a:hover, .h4 a:focus, h5 a:hover, h5 a:focus, .h5 a:hover, .h5 a:focus, h6 a:hover, h6 a:focus, .h6 a:hover, .h6 a:focus, .comment-author a:hover, .comment-author a:focus, .entry-author-name a:hover, .entry-author-name a:focus,
			.header-section a:not(.button):hover, .header-section a:not(.button):focus,
			 .header-section .action-toggle:hover, .header-section .action-toggle:focus, .header-section .menu .sub-menu a:not(.button):hover, .header-section .menu .sub-menu a:not(.button):focus, 
			.header-section .menu .sub-menu .action-toggle:hover, 
			.header-section .menu .sub-menu .action-toggle:focus, 
			.header-section-vertical a:not(.button):hover, .header-section-vertical a:not(.button):focus,
			 .header-section-vertical .action-toggle:hover, .header-section-vertical .action-toggle:focus, 
			 .header-section-vertical .menu .sub-menu a:not(.button):hover, 
			 .header-section-vertical .menu .sub-menu a:not(.button):focus, 
			.header-section-vertical .menu .sub-menu .action-toggle:hover, 
			.header-section-vertical .menu .sub-menu .action-toggle:focus
			',

			'hover' => 'a:hover, a:focus, .action-toggle:hover, .action-toggle:focus',

		),
		'devices' => false, // or array( 'desktop', 'tablet', 'mobile );
		'hide_groups' => array(
			'extra',
			'shadow',
			'sizing',
		),
		'disabled_props' => array(
			'font_family',
			'background_image',
			'background_position',
			'background_attachment',
			'background_repeat',
			'background_size',
		),
		'states' => array(
			'default' => esc_html__( 'Default', 'onestore' ),
			'hover' => esc_html__( 'Hover', 'onestore' ),
		),
		'control' => array(
			'label'  => esc_html__( 'Link', 'onestore' ),
		),
	)
);

for ( $i = 1; $i < 6; $i ++ ) {
	$selector  = 'h' . $i;
	switch ( $i ) {
		case 1:
			$selector .= ', .title, .entry-title, .page-title';
			break;
		case 3:
			$selector .= ', legend, .small-title, .entry-small-title, .comments-title, .comment-reply-title, .page-header .page-title';
			break;
		case 4:
			$selector .= ', .widget-title';
			break;

	}

	OneStore_Customizer::add_field(
		'h' . $i,
		array(
			'control_class' => 'css',
			'selector' => $selector, // apply for css tyle only.
			'devices' => false, // or array( 'desktop', 'tablet', 'mobile );
			'states' => 'default', // or array( 'default', 'hover', '...' );
			'hide_groups' => array(
				'sizing',
				'extra',
			),
			'disabled_props' => array(
				'background_image',
				'background_position',
				'background_attachment',
				'background_repeat',
				'background_size',
			),
			'control' => array(
				'label'      => sprintf( esc_html__( 'Heading %1$s (H%1$s)', 'onestore' ), $i ),
			),
		)
	);

}

// blockquote
OneStore_Customizer::add_field(
	'blockquote',
	array(
		'control_class' => 'css',
		'selector' => 'blockquote, .blockquote', // apply for css tyle only.
		'devices' => false, // or array( 'desktop', 'tablet', 'mobile );
		'states' => 'default', // or array( 'default', 'hover', '...' );
		'control' => array(
			'label'  => esc_html__( 'Blockquote', 'onestore' ),
		),
	)
);


OneStore_Customizer::add_field(
	'button',
	array(
		'control_class' => 'css',
		'selector' => array(
			'default' => 'button,
				input[type="button"],
				input[type="reset"],
				input[type="submit"],
				.button,
				a.button,
				.action-toggle.button,
				select.button,
				a.wp-block-button__link',

			'hover' => 'button:hover,
				input[type="button"]:hover,
				input[type="reset"]:hover,
				input[type="submit"]:hover,
				.button:hover,
				a.button:hover,
				.action-toggle.button:hover,
				select.button:hover,
				a.wp-block-button__link:hover,
				button.active,
				input[type="button"].active,
				input[type="reset"].active,
				input[type="submit"].active,
				.button.active,
				a.button.active,
				.action-toggle.button.active,
				select.button.active,
				a.wp-block-button__link.active',

		),
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => array(
			'default' => esc_html__( 'Default', 'onestore' ),
			'hover' => esc_html__( 'Hover/Active', 'onestore' ),
		), // or array( 'default', 'hover', '...' );
		'control' => array(
			'label'  => esc_html__( 'Button', 'onestore' ),
		),
	)
);

OneStore_Customizer::add_field(
	'button_sm',
	array(
		'control_class' => 'css',
		'selector' => 'button.sm,
		input[type="button"].sm,
		input[type="reset"].sm,
		input[type="submit"].sm,
		.button.sm,
		a.button.sm,
		.action-toggle.button.sm,
		select.button.sm,
		a.wp-block-button__link.sm', // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => 'default', // or array( 'default', 'hover', '...' );
		'control' => array(
			'label'  => esc_html__( 'Button Small', 'onestore' ),
		),
	)
);

OneStore_Customizer::add_field(
	'button_lg',
	array(
		'control_class' => 'css',
		'selector' => 'button.lg,
		input[type="button"].lg,
		input[type="reset"].lg,
		input[type="submit"].lg,
		.button.lg,
		a.button.lg,
		.action-toggle.button.lg,
		select.button.lg,
		a.wp-block-button__link.lg', // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => 'default', // or array( 'default', 'hover', '...' );
		'control' => array(
			'label'  => esc_html__( 'Button Large', 'onestore' ),
		),
	)
);

OneStore_Customizer::add_field(
	'input',
	array(
		'control_class' => 'css',
		'selector' => array(
			'default' => 'input[type="text"], input[type="password"], input[type="color"], input[type="date"], input[type="datetime-local"],
				 input[type="email"], input[type="month"], input[type="number"], input[type="search"], 
				 input[type="tel"], input[type="time"], input[type="url"], input[type="week"], 
				 .input, select, textarea, 
				.search-field, span.select2-container .select2-selection,
			span.select2-container.select2-container--open .select2-dropdown, .search-form',
			'focus' => 'input[type="text"]:focus, input[type="password"]:focus, 
				input[type="color"]:focus, input[type="date"]:focus, input[type="datetime-local"]:focus, 
				input[type="email"]:focus, input[type="month"]:focus, input[type="number"]:focus, input[type="search"]:focus, 
				input[type="tel"]:focus, input[type="time"]:focus, input[type="url"]:focus, input[type="week"]:focus,
				 .input:hover, .input:focus, select:focus, textarea:focus, .search-field:focus, 
				span.select2-container.select2-container--open .select2-selection',
		), // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => array(
			'default' => esc_html__( 'Default', 'onestore' ),
			'focus' => esc_html__( 'Focus', 'onestore' ),
		), // or array( 'default', 'hover', '...' );
		'control' => array(
			'label'  => esc_html__( 'Form Input', 'onestore' ),
		),
	)
);



OneStore_Customizer::add_field(
	'title',
	array(
		'control_class' => 'css',
		'selector' => '.title, .entry-title, .page-title',
		'devices' => true, // or array( 'desktop', 'tablet', 'mobile );
		'states' => 'default', // or array( 'default', 'hover', '...' );
		'control' => array(
			'label'  => esc_html__( 'Title', 'onestore' ),
			'description' => esc_html__( 'Used on Default Post title and Static Page title. By default, it uses H1 styles.', 'onestore' ),
		),
	)
);

OneStore_Customizer::add_field(
	'small_title',
	array(
		'control_class' => 'css',
		'selector' => 'legend, .small-title, .entry-small-title, .comments-title, .comment-reply-title, .page-header .page-title',
		'devices' => true, // or array( 'desktop', 'tablet', 'mobile );
		'states' => 'default', // or array( 'default', 'hover', '...' );
		'control' => array(
			'label'  => esc_html__( 'Small Title', 'onestore' ),
			'description' => esc_html__( 'Used on Grid Post title, and other subsidiary headings like "Leave a Reply", "2 Comments", etc. By default, it uses H3 styles.', 'onestore' ),
		),
	)
);


OneStore_Customizer::add_field(
	'meta',
	array(
		'control_class' => 'css',
		'selector' => '.entry-meta, .comment-metadata, .widget .post-date, .widget_rss .rss-date, .product_meta',
		'devices' => true, // or array( 'desktop', 'tablet', 'mobile );
		'states' => 'default',
		'control' => array(
			'label'  => esc_html__( 'Meta Title', 'onestore' ),
			'description' => esc_html__( 'Used on Post meta, Widget meta, Comments meta, and other small info text.', 'onestore' ),
		),
	)
);


OneStore_Customizer::add_field(
	'border_color',
	array(
		'control_class' => 'color',
		'control' => array(
			'label'       => esc_html__( 'Line / border color', 'onestore' ),
			'description' => esc_html__( 'Used on &lt;hr&gt; and default border color of all elements.', 'onestore' ),
		),
		'css_live' => array(
			array(
				'type'     => 'css',
				'element'  => '*',
				'property' => 'border-color',
			),
		),
	)
);

OneStore_Customizer::add_field(
	'subtitle_color',
	array(
		'control_class' => 'color',
		'control' => array(
			'label'       => esc_html__( 'Subtitle background color', 'onestore' ),
			'description' => esc_html__( 'Used as background color of &lt;code&gt;, &lt;pre&gt;, tagclouds, and archive title. Usually slightly darker or lighter than the page background color.', 'onestore' ),
		),
		'css_live' => array(
			array(
				'type'     => 'css',
				'element'  => 'pre, code, .page-header, .tagcloud a, .navigation.pagination .current, span.select2-container .select2-selection--multiple .select2-selection__rendered li.select2-selection__choice, .wp-block-table.is-style-stripes tr:nth-child(odd)',
				'property' => 'background-color',
			),
		),
	)
);





OneStore_Customizer::section_end();
