<?php
/**
 * Contains methods for customizing the theme customization screen.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class OneStore_Customizer {

	/**
	 * Singleton instance
	 *
	 * @var OneStore_Customizer
	 */
	private static $instance;

	private static $conditions  = array();
	private static $defaults  = array();
	private static $css_live = null;
	/**
	 * // array. id is section id, value is preview url.
	 *
	 * @var array
	 */
	private static $preview_urls = array();
	protected static $current_panel = false;
	protected static $current_section = false;
	protected static $current_priority = 10;
	protected static $wp_customize = false;
	protected static $controls = array();
	protected static $sections = array();
	protected static $panels = array();

	/**
	 * ====================================================
	 * Singleton & constructor functions
	 * ====================================================
	 */

	/**
	 * Get singleton instance.
	 *
	 * @return OneStore_Customizer
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Class constructor
	 */
	protected function __construct() {
		// Google Fonts CSS.
		add_action( 'onestore/frontend/before_enqueue_main_css', array( $this, 'enqueue_frontend_google_fonts_css' ) );

		// Default values, postmessages, contexts.
		add_filter( 'onestore/customizer/setting_postmessages', array( $this, 'add_setting_postmessages' ) );
		add_filter( 'onestore/customizer/control_contexts', array( $this, 'add_control_contexts' ) );

		// Customizer page.
		add_action( 'customize_register', array( $this, 'register_custom_controls' ), 1 );
		add_action( 'customize_register', array( $this, 'register_settings' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		if ( is_customize_preview() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_preview_scripts' ) );
			add_action( 'wp_head', array( $this, 'print_preview_styles' ), 20 );
			add_action( 'wp_footer', array( $this, 'print_preview_scripts' ), 20 );
		}
	}

	public static function load_setup() {
		if ( ! did_action( 'onestore/customize/setup' ) ) {
			do_action( 'onestore/customize/setup' );
		}
	}

	public static function wp_loaded() {
		self::load_setup();
	}

	public static function get_css_live() {
		if ( is_null( self::$css_live ) ) {
			self::$css_live = array();
			self::load_setup();
		}
		return self::$css_live;
	}

	public static function get_mobile_header_rows() {
		$rows = array( 'main' => esc_html__( 'Mobile Main Bar', 'onestore' ) );
		if ( onestore_is_plus() ) {
			$rows = array(
				'top' => esc_html__( 'Mobile Top Bar', 'onestore' ),
				'main' => esc_html__( 'Mobile Main Bar', 'onestore' ),
				'bottom' => esc_html__( 'Mobile Bottom Bar', 'onestore' ),
			);
		}
		return $rows;
	}



	public static function set_preview_url( $section_id, $url ) {
		self::$preview_urls[ $section_id ] = $url;
	}

	public static function set_wp_customize() {
		global $wp_customize;
		if ( ! self::$wp_customize ) {
			self::$wp_customize = $wp_customize;
		}
	}

	public static function set_current_priority( $priority ) {
		self::$current_priority = $priority;
	}


	public static function section_start( $id, $title = false, $priority = null, $spacer = false ) {
		if ( ! self::$wp_customize ) {
			self::set_wp_customize();
		}

		if ( ! self::$wp_customize ) {
			return;
		}

		self::$current_section = $id;

		if ( ! $title ) {
			return;
		}

		$args = array(
			'title'       => $title,
		);

		if ( ! $priority ) {
			if ( self::$current_priority ) {
				$args['priority'] = self::$current_priority;
			}
		} else {
			$args['priority'] = $priority;
		}

		if ( self::$current_panel ) {
			$args['panel'] = self::$current_panel;
		}

		if ( $spacer ) {
			self::$wp_customize->add_section(
				new OneStore_Customize_Section_Spacer(
					self::$wp_customize,
					$id . '_spacer',
					$args
				)
			);
		}

		self::$wp_customize->add_section(
			$id,
			$args
		);

	}

	public static function section_end() {
		self::$current_section = false;
	}

	public static function panel_start( $id, $title = false, $priority = 10 ) {
		self::$current_panel = $id;
		if ( ! $title ) {
			return;
		}
		if ( ! self::$wp_customize ) {
			self::set_wp_customize();
		}

		if ( ! self::$wp_customize ) {
			return;
		}

		self::$wp_customize->add_panel(
			$panel,
			array(
				'title'       => $title,
				'priority'    => $id,
			)
		);
	}

	public static function no_panel() {
		self::panel_end();
	}

	public static function panel_end() {
		self::$current_panel = false;
	}


	public static function add_heading( $id, $title, $priority = null, $condition = null ) {
		if ( ! self::$wp_customize ) {
			self::set_wp_customize();
		}

		if ( ! self::$wp_customize ) {
			return;
		}

		if ( ! class_exists( 'OneStore_Customize_Control_Heading' ) ) {
			return;
		}

		$args = array(
			'section'     => self::$current_section,
			'settings'    => array(),
			'label'       => $title,
		);

		if ( ! $priority ) {
			if ( self::$current_priority ) {
				$args['priority'] = self::$current_priority;
			}
		} else {
			$args['priority'] = $priority;
		}

		self::$wp_customize->add_control(
			new OneStore_Customize_Control_Heading(
				self::$wp_customize,
				$id,
				$args
			)
		);

		if ( $condition && ! empty( $condition ) ) {
			self::$conditions[ $id ] = $condition;
		}
	}

	public static function add_noti( $id, $message, $priority = null, $condition = null ) {
		if ( ! self::$wp_customize ) {
			self::set_wp_customize();
		}

		if ( ! self::$wp_customize ) {
			return;
		}

		if ( ! class_exists( 'OneStore_Customize_Control_Blank' ) ) {
			return;
		}

		$args = array(
			'section'     => self::$current_section,
			'settings'    => array(),
			'description'       => $message,
		);

		if ( ! $priority ) {
			if ( self::$current_priority ) {
				$args['priority'] = self::$current_priority;
			}
		} else {
			$args['priority'] = $priority;
		}

		self::$wp_customize->add_control(
			new OneStore_Customize_Control_Blank(
				self::$wp_customize,
				$id,
				$args
			)
		);

		if ( $condition && ! empty( $condition ) ) {
			self::$conditions[ $id ] = $condition;
		}

	}

	/**
	 * @see https://developer.wordpress.org/themes/customize-api/customizer-objects/
	 *
	 * @param [type] $id
	 * @param [type] $args
	 * @return void
	 */
	public static function add_field( $id, $args ) {

		if ( ! self::$wp_customize ) {
			self::set_wp_customize();
		}

		$args = wp_parse_args(
			$args,
			array(
				'devices'    => false, // true or false.
				'control_class'    => '',
				'setting'    => '',
				'control'    => '',
				'section' => '',
				'condition'  => '',
				'refresh'  => '',
				'css_live'  => '',
				'live_css'  => '',
				'selector'  => '',
			)
		);

		if ( 'css' == $args['control_class'] || 'OneStore_Customize_Control_CSS' == $args['control_class'] ) {
			return self::add_css_field( $id, $args );
		}

		$css_live_args = is_array( $args['css_live'] ) ? $args['css_live'] : false;
		if ( ! $css_live_args ) {
			$css_live_args = is_array( $args['live_css'] ) ? $args['live_css'] : false;
		}
		if ( ! $css_live_args ) {
			$css_live_args = is_array( $args['selector'] ) ? $args['selector'] : false;
		}

		/**
		 * @see https://developer.wordpress.org/themes/customize-api/customizer-objects/
		 */
		$setting_args = wp_parse_args(
			$args['setting'],
			array(
				'type' => 'theme_mod', // or 'option'.
				'capability' => 'edit_theme_options',
				'transport' => 'refresh', // or postMessage.
				'sanitize_callback' => null,
				'sanitize_js_callback' => null, // Basically to_json.
			)
		);

		$control_args = wp_parse_args(
			$args['control'],
			array(
				'section' => null,
				'label' => null,
				'description' => null,
			)
		);
		$refresh_args = wp_parse_args(
			$args['refresh'],
			array(
				'selector' => null,
				'settings' => null,
				'render_callback' => null,
			)
		);
		$control_args['section'] = self::$current_section;

		if ( ! isset( $control_args['priority'] ) ) {
			if ( self::$current_priority ) {
				$control_args['priority'] = self::$current_priority;
			}
		} else {
			$control_args['priority'] = $priority;
		}

		$typo_keys = false;
		$keys = array();

		if ( ! $setting_args['sanitize_callback'] ) {
			switch ( $args['control_class'] ) {
				case 'toggle':
				case 'OneStore_Customize_Control_Toggle':
					$args['control_class'] = 'OneStore_Customize_Control_Toggle';
					$setting_args['sanitize_callback'] = array( 'OneStore_Customizer_Sanitization', 'toggle' );
					break;
				case 'dimensions':
				case 'OneStore_Customize_Control_Dimensions':
					$args['control_class'] = 'OneStore_Customize_Control_Dimensions';
					$setting_args['sanitize_callback'] = array( 'OneStore_Customizer_Sanitization', 'dimensions' );
					break;
				case 'slider':
				case 'OneStore_Customize_Control_Slider':
					$args['control_class'] = 'OneStore_Customize_Control_Slider';
					$setting_args['sanitize_callback'] = array( 'OneStore_Customizer_Sanitization', 'dimension' );
					break;
				case 'dimension':
				case 'OneStore_Customize_Control_Dimension':
					$args['control_class'] = 'OneStore_Customize_Control_Dimension';
					$setting_args['sanitize_callback'] = array( 'OneStore_Customizer_Sanitization', 'dimension' );
					break;
				case 'typography':
				case 'typo':
				case 'OneStore_Customize_Control_Typography':
					$args['control_class'] = 'OneStore_Customize_Control_Typography';
					$setting_args['sanitize_callback'] = array( 'OneStore_Customizer_Sanitization', 'typography' );
					$typo_keys = array(
						'font_family'    => $id . '_font_family',
						'font_weight'    => $id . '_font_weight',
						'font_style'     => $id . '_font_style',
						'text_transform' => $id . '_text_transform',
						'font_size'      => $id . '_font_size',
						'line_height'    => $id . '_line_height',
						'letter_spacing' => $id . '_letter_spacing',

						'font_size__tablet'      => $id . '_font_size__tablet',
						'line_height__tablet'    => $id . '_line_height__tablet',
						'letter_spacing__tablet' => $id . '_letter_spacing__tablet',

						'font_size__mobile'      => $id . '_font_size__mobile',
						'line_height__mobile'    => $id . '_line_height__mobile',
						'letter_spacing__mobile' => $id . '_letter_spacing__mobile',
					);
					break;
				case 'color':
				case 'OneStore_Customize_Control_Color':
					$args['control_class'] = 'OneStore_Customize_Control_Color';
					$setting_args['sanitize_callback'] = array( 'OneStore_Customizer_Sanitization', 'color' );
					break;
				case 'image':
				case 'WP_Customize_Image_Control':
					$args['control_class'] = 'WP_Customize_Image_Control';
					$setting_args['sanitize_callback'] = array( 'OneStore_Customizer_Sanitization', 'image' );
					break;
				case 'radio_image':
				case 'radio_images':
				case 'image_choices':
				case 'OneStore_Customize_Control_RadioImage':
					$args['control_class'] = 'OneStore_Customize_Control_RadioImage';
					$setting_args['sanitize_callback'] = array( 'OneStore_Customizer_Sanitization', 'select' );
					break;
				case 'builder':
				case 'OneStore_Customize_Control_Builder':
					$args['control_class'] = 'OneStore_Customize_Control_Builder';
					$setting_args['sanitize_callback'] = array( 'OneStore_Customizer_Sanitization', 'multiselect' );
					break;

				default:
					switch ( $control_args['type'] ) {
						case 'select':
							$setting_args['sanitize_callback'] = array( 'OneStore_Customizer_Sanitization', 'select' );
							break;
						case 'textarea':
							$setting_args['sanitize_callback'] = array( 'OneStore_Customizer_Sanitization', 'html' );
							break;
					}
			}
		}

		if ( ( $refresh_args['selector'] && $refresh_args['render_callback'] ) || ( $css_live_args && ! empty( $css_live_args ) ) ) {
			$setting_args['transport'] = 'postMessage';
		}

		if ( ! $setting_args['transport'] ) {
			$setting_args['transport'] = 'refresh';
		}

		if ( ! isset( $setting_args['sanitize_callback'] ) ) {
			$setting_args['sanitize_callback'] = array( 'OneStore_Customizer_Sanitization', 'html' );
		}

		if ( $args['devices'] ) {
			$keys = array(
				$id,
				$id . '__tablet',
				$id . '__mobile',
			);
			foreach ( $keys as $key ) {
				$setting_args['default'] = onestore_get_default_value( $key );
				if ( self::$wp_customize ) {
					self::$wp_customize->add_setting(
						$key,
						array_merge(
							array( 'sanitize_callback' => 'sanitize_text_field' ),
							$setting_args
						)
					); // WPCS: sanitization ok.
				}
			}
			$control_args['settings'] = $keys;
		} else {
			if ( isset( $control_args['settings'] ) && ! empty( $control_args['settings'] ) ) {
				$keys = $control_args['settings'];
				foreach ( $keys as $key ) {
					$setting_args['default'] = onestore_get_default_value( $key );
					if ( self::$wp_customize ) {
						self::$wp_customize->add_setting(
							$key,
							array_merge(
								array( 'sanitize_callback' => 'sanitize_text_field' ),
								$setting_args
							)
						); // WPCS: sanitization ok.
					}
				}
				$control_args['settings'] = $keys;

			} else {
				$keys = array( $id );
				if ( self::$wp_customize ) {
					$setting_args['default'] = onestore_get_default_value( $id );
					self::$wp_customize->add_setting(
						$id,
						array_merge(
							array( 'sanitize_callback' => 'sanitize_text_field' ),
							$setting_args
						)
					); // WPCS: sanitization ok.
				}
			}
		}

		if ( self::$wp_customize ) {
			if ( $args['control_class'] ) {
				self::$wp_customize->add_control(
					new $args['control_class'](
					self::$wp_customize,
					$id,
					$control_args
					)
				);
			} else {
				self::$wp_customize->add_control( $id, $control_args );
			}
			if ( $args['condition'] && ! empty( $args['condition'] ) ) {
				self::$conditions[ $id ] = $args['condition'];
			}
		}

		/**
		 * @see https://developer.wordpress.org/reference/classes/wp_customize_partial/__construct/
		 */
		if ( self::$wp_customize ) {
			if ( $refresh_args['selector'] && $refresh_args['render_callback'] ) {
				$refresh_args['settings'] = array_values( $keys );
				if ( ! isset( $refresh_args['container_inclusive'] ) ) {
					$refresh_args['container_inclusive'] = true;
				}
				self::$wp_customize->selective_refresh->add_partial(
					$id,
					$refresh_args
				);
			}
		}

		if ( $css_live_args && ! empty( $css_live_args ) ) {
			$media_queries = OneStore::instance()->get_media_queries();
			if ( $typo_keys ) {
				foreach ( array( 'font_family', 'font_weight', 'font_style', 'text_transform', 'font_size', 'line_height', 'letter_spacing' ) as $prop ) {
					$property = str_replace( '_', '-', $prop );

					$rules = array();

					$rules[] = array(
						'type'     => 'font_family' === $prop ? 'font' : 'css',
						'element'  => $css_live_args['selector'],
						'property' => $property,
					);

					self::$css_live[ $id . '_' . $prop ] = $rules;

					// Responsive.
					if ( in_array( $prop, array( 'font_size', 'line_height', 'letter_spacing' ) ) ) {
						// Tablet.
						$rules__tablet = $rules;
						foreach ( $rules__tablet as &$rule ) {
							$rule['media'] = $media_queries['__tablet'];
						}
						self::$css_live[ $id . '_' . $prop . '__tablet' ] = $rules__tablet;

						// Mobile.
						$rules__mobile = $rules;
						foreach ( $rules__mobile as &$rule ) {
							$rule['media'] = $media_queries['__mobile'];
						}
						self::$css_live[ $id . '_' . $prop . '__mobile' ] = $rules__mobile;
					}
				} // end if typo.
			} else {

				if ( $args['devices'] ) {
					foreach ( $media_queries as $suffix => $media ) {

						foreach ( $css_live_args as $k => $v ) {
							$v['media'] = $media;
							$css_live_args[ $k ] = $v;
						}

						self::$css_live[ $id . $suffix ] = $css_live_args;
					}
				} else {
					self::$css_live[ $id ] = $css_live_args;
				}
			}
		}

	}

	protected static function add_css_field( $id, $args ) {

		$group_setting_keys = self::get_css_keys();
		$keys = [];

		$control_args = wp_parse_args(
			$args['control'],
			array(
				'section' => null,
				'label' => null,
				'description' => null,
			)
		);

		$is_locked = false;
		if ( isset( $control_args['locked'] ) && $control_args['locked'] ) {
			$is_locked = true;
		}

		if ( ! isset( $args['states'] ) || ! is_array( $args['states'] ) ) {
			$states = array(
				'default' => esc_html__( 'Default', 'onestore' ),
			);
		} else {
			$states = $args['states'];
		}

		// Add more rules for property.
		$css_rules = ( isset( $args['css_rules'] ) && is_array( $args['css_rules'] ) ) ? $args['css_rules'] : false;
		$css_selector = isset( $args['selector'] ) ? $args['selector'] : false;
		$css_var = isset( $args['css_var'] ) && $args['css_var'] ? true : false;
		$disabled_props = array();
		if ( isset( $args['disabled_props'] ) && is_array( $args['disabled_props'] ) ) {
			foreach ( $args['disabled_props'] as $p ) {
				$disabled_props[ $p ] = true;
			}
		}
		$control_args['section'] = self::$current_section;

		if ( ! isset( $control_args['priority'] ) ) {
			if ( self::$current_priority ) {
				$control_args['priority'] = self::$current_priority;
			}
		} else {
			$control_args['priority'] = $priority;
		}

		$all_devices = array( 'desktop', 'tablet', 'mobile' );
		$devices = false;
		if ( ! isset( $args['devices'] ) || ! is_array( $args['devices'] ) ) {
			if ( 'all' == $args['devices'] ) {
				$devices = $all_devices;
			}
		} else {
			$devices = $args['devices'];
		}

		if ( ! $devices || empty( $devices ) ) {
			$devices = array( 'desktop' );
		}

		$hide_groups = array();
		if ( isset( $args['hide_groups'] ) && is_array( $args['hide_groups'] ) ) {
			foreach ( $args['hide_groups'] as $group_key ) {
				$hide_groups[ $group_key ] = true;
			}
		}

		$media_queries = OneStore::instance()->get_media_queries();

		$mapping = array();

		foreach ( $devices as $device_id ) {
			foreach ( $states as $state => $state_label ) {
				foreach ( $group_setting_keys as $group_key => $setting_keys ) {
					if ( isset( $hide_groups[ $group_key ] ) && $hide_groups[ $group_key ] ) {
						continue;
					}
					foreach ( $setting_keys as $prop => $pattern ) {

						// Skip property.
						if ( isset( $disabled_props[ $prop ] ) ) {
							continue;
						}

						$key = $id . '_' . $prop;
						if ( 'default' != $state ) {
							$key .= '_' . $state;
						}
						if ( 'desktop' != $device_id ) {
							$key .= '__' . $device_id;
						}

						$keys[] = $key;
						$mapping[ $key ] = array(
							'device' => $device_id,
							'state' => $state,
							'prop' => $prop,
						);
						if ( $css_selector ) {
							$element_selector = '';
							if ( is_array( $css_selector ) ) {
								if ( isset( $css_selector[ $state ] ) ) {
									$element_selector = $css_selector[ $state ];
								}
							} else {
								$element_selector = $css_selector;
							}

							if ( $element_selector ) {
								$property = str_replace( '_', '-', $prop );
								if ( $css_var ) {
									$property = '--' . $property;
								}
								$rules = array();
								$rule_type = 'font_family' === $prop ? 'font' : 'css';
								$rule = array(
									'type'     => $rule_type,
									'element'  => $element_selector,
									'property' => $property,
								);

								if ( $pattern ) {
									$rule['pattern'] = $pattern;
								}

								if ( 'desktop' != $device_id ) {
									$rule['media'] = $media_queries[ '__' . $device_id ];
								}

								// Overwtire csss rules.
								if ( $css_rules && isset( $css_rules[ $prop ] ) ) {
									foreach ( $css_rules[ $prop ] as $rk => $rr ) {
										$more_rr = $rr;
										if ( ! isset( $more_rr['type'] ) ) {
											$more_rr['type'] = $rule_type;
										}

										$more_element = false;

										if ( is_array( $rr['element'] ) ) {
											if ( isset( $rr['element'][ $state ] ) ) {
												$more_element = $rr['element'][ $state ];
											}
										} else {
											if ( isset( $rr['element'] ) ) {
												$more_element = $rr['element'];
											}
										}

										$more_rr['element'] = $more_element;

										if ( ! isset( $more_rr['property'] ) ) {
											$more_rr['property'] = $property;
										}

										if ( 'desktop' != $device_id ) {
											$more_rr['media'] = $media_queries[ '__' . $device_id ];
										}
										if ( $more_element ) {
											$rules[] = $more_rr;
										}
									}
								} else {
									$rules[] = $rule;
								}

								self::$css_live[ $key ] = $rules;
							}
						}

						if ( self::$wp_customize ) {
							$settings_args = array(
								'default'     => onestore_get_default_value( $key ),
								'transport'   => 'postMessage',
								'sanitize_callback' => array( 'OneStore_Customizer_Sanitization', 'text' ),
							);
							self::$wp_customize->add_setting(
								$key,
								array_merge(
									array( 'sanitize_callback' => 'sanitize_text_field' ),
									$settings_args
								)
							); // WPCS: sanitization ok.
						}
					} // end settings key.
				}// end group settings keys.
			} // end states.
		} // end devices.

		if ( self::$wp_customize ) {

			$control_args['states'] = $states;
			$control_args['settings'] = $keys;
			$control_args['mapping'] = $mapping;
			$control_args['devices'] = $devices;
			$control_args['hide_groups'] = $hide_groups;
			$control_args['disabled_props'] = $disabled_props;

			self::$wp_customize->add_control(
				new OneStore_Customize_Control_CSS(
					self::$wp_customize,
					$id,
					$control_args
				)
			);

			if ( $args['condition'] && ! empty( $args['condition'] ) ) {
				self::$conditions[ $id ] = $args['condition'];
			}
		}

	} // end method.

	static function get_css_keys() {
		$setting_keys = array(
			'spacings' => array(
				'padding' => '',
				'margin' => '',
			),
			'outline' => array(
				'outline_width' => '',
				'outline_style' => '',
				'outline_color' => '',
				'outline_offset' => '',
			),
			'typo' => array(
				'font_family' => '',
				'font_weight' => '',
				'font_style' => '',
				'text_transform' => '',
				'font_size' => '',
				'line_height' => '',
				'letter_spacing' => '',
				'color' => '',
			),
			'background' => array(
				'background_image' => 'url("$")',
				'background_color' => '',
				'background_position' => '',
				'background_repeat' => '',
				'background_attachment' => '',
				'background_size' => '',
			),
			'border' => array(
				'border_width' => '',
				'border_color' => '',
				'border_style' => '',
				'border_radius' => '',
			),
			'shadow' => array(
				'box_shadow' => '',
			),
			'sizing' => array(
				'width' => '',
				'min_width' => '',
				'max_width' => '',
				'height' => '',
				'min_height' => '',
				'max_height' => '',
			),
			'extra' => array(
				'display' => '',
				'float' => '',
				'clear' => '',
				'visibility' => '',
				'opacity' => '',
				'align_items' => '',
				'justify_content' => '',
				'text_align' => '',
			),

		);
		return $setting_keys;
	}

	/**
	 * ====================================================
	 * Hook functions
	 * ====================================================
	 */

	/**
	 * Enqueue Google Fonts CSS on frontend.
	 */
	public function enqueue_frontend_google_fonts_css() {
		// Customizer Google Fonts.
		$google_fonts_url = $this->generate_active_google_fonts_embed_url();
		if ( ! empty( $google_fonts_url ) ) {
			wp_enqueue_style( 'onestore-google-fonts', $google_fonts_url, array(), ONESTORE_VERSION );
		}
	}


	/**
	 * Add postmessage rules for some Customizer settings.
	 * Triggered via filter to allow modification by users.
	 *
	 * @param array $postmessages
	 * @return array
	 */
	public function add_setting_postmessages( $postmessages = array() ) {
		$add = include ONESTORE_INCLUDES_DIR . '/customizer/postmessages.php';
		self::load_setup();

		if ( ! is_array( self::$css_live ) ) {
			self::$css_live = array();
		}

		$add = array_merge( $add, self::$css_live );
		return array_merge_recursive( $postmessages, $add );
	}

	/**
	 * Add dependency contexts for some Customizer settings.
	 * Triggered via filter to allow modification by users.
	 *
	 * @param array $contexts
	 * @return array
	 */
	public function add_control_contexts( $contexts = array() ) {
		$add = include ONESTORE_INCLUDES_DIR . '/customizer/contexts.php';
		self::load_setup();

		if ( ! is_array( self::$conditions ) ) {
			self::$conditions = array();
		}
		$add = array_merge( $add, self::$conditions );
		return array_merge_recursive( $contexts, $add );
	}

	/**
	 * Register custom customizer controls.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function register_custom_controls( $wp_customize ) {
		require_once ONESTORE_INCLUDES_DIR . '/customizer/class-customizer-sanitization.php';

		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-section-spacer.php';

		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control.php';

		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-hr.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-heading.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-blank.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-toggle.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-color.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-css.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-shadow.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-slider.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-dimension.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-dimensions.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-typography.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-multicheck.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-radioimage.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-builder.php';

		if ( onestore_show_pro_teaser() ) {
			require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-section-pro-link.php';
			require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-section-pro-teaser.php';
			require_once ONESTORE_INCLUDES_DIR . '/customizer/custom-controls/class-customize-control-pro-teaser.php';
		}
	}

	/**
	 * Register customizer sections and settings.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function register_settings( $wp_customize ) {

		self::load_setup();

		/**
		 * Register settings
		 */
		$defaults = $this->get_setting_defaults();

		// Sections and Panels.
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/_sections.php';

		// Layout.
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/header--builder.php';

		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/header--logo.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/header--html.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/header--button.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/header--search.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/header--cart.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/header--account.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/header--social.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/footer--builder.php';

		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/footer--copyright.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/footer--social.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/footer--scroll-to-top.php';

		// Global Settings.
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/global--color-palette.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/global--google-fonts.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/global--social.php';

		// Page Settings.
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/layout-settings.php';

		// Blog.
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/blog--archive.php';
		require_once ONESTORE_INCLUDES_DIR . '/customizer/options/blog--single.php';

		// Item Block Builder.
		$wc_blocks = array(
			'wc_title'      => esc_html__( 'Title', 'onestore' ),
			'wc_price'      => esc_html__( 'Price', 'onestore' ),
			'wc_price_less' => esc_html__( 'Price Less', 'onestore' ),
			'wc_rating'      => esc_html__( 'Rating', 'onestore' ),
			'wc_sale'      => esc_html__( 'Sale', 'onestore' ),
			'wc_sale_percent'      => esc_html__( 'Sale Percentage', 'onestore' ),
			'wc_category'    => esc_html__( 'Category', 'onestore' ),
			'wc_add_to_cart' => esc_html__( 'Add to cart', 'onestore' ),
			'wc_add_to_cart_icon' => esc_html__( 'Add to cart icon', 'onestore' ),
			'wc_wishlist_icon' => esc_html__( 'Wishlist Icon', 'onestore' ),
			'wc_quick_view_icon' => esc_html__( 'Quick View Icon', 'onestore' ),
		);

		if ( defined( 'SAVP__PATH' ) ) {
			$wc_blocks['wc_swatches'] = esc_html__( 'Swatches', 'onestore' );
		}

		$items = array();

		// Item Block Builder.
		$post_blocks = array(
			'post_title'      => esc_html__( 'Title', 'onestore' ),
			'post_cats'      => esc_html__( 'Categories', 'onestore' ),
			'post_tags' => esc_html__( 'Tags', 'onestore' ),
			'post_excerpt'      => esc_html__( 'Excerpt', 'onestore' ),
			'post_meta'      => esc_html__( 'Meta', 'onestore' ),
			'post_link'      => esc_html__( 'Read more', 'onestore' ),

		);

		$items['wc'] = array(
			'setting_key' => 'woocommerce_index_item_elements',
			'section' => 'woocommerce_product_catalog',
			'start_priority' => 70,
			'items' => $wc_blocks,
		);
		$items['blog'] = array(
			'setting_key' => 'posts_index_item_elements',
			'section' => 'onestore_section_blog_index',
			'start_priority' => 20,
			'items' => $post_blocks,
		);

		$item_block_builders = apply_filters( 'onestore/customizer/item_block_builders', $items );
		foreach ( $item_block_builders as $item_builder_args ) {
			$this->add_settings_item_block( $item_builder_args );
		}

	}

	public function add_settings_item_block( $item_builder_args ) {
		global $wp_customize;
		$builder_setting_key = $item_builder_args['setting_key'];
		$priority = $item_builder_args['start_priority'];
		$section = $item_builder_args['section'];

		$wp_customize->add_control(
			new OneStore_Customize_Control_Heading(
				$wp_customize,
				'heading_' . $builder_setting_key,
				array(
					'section'     => $section,
					'settings'    => array(),
					'label'       => esc_html__( 'Item Elements', 'onestore' ),
					'priority'    => $priority,
				)
			)
		);
		$settings = array(
			'top'    => $builder_setting_key . '_top',
			'thumb_left'  => $builder_setting_key . '_thumb_left',
			'thumb'  => $builder_setting_key . '_thumb',
			'thumb_right'  => $builder_setting_key . '_thumb_right',
			'thumb_bottom'  => $builder_setting_key . '_thumb_bottom',
			'body_before'  => $builder_setting_key . '_body_before',
			'body_left'  => $builder_setting_key . '_body_left',
			'body'  => $builder_setting_key . '_body',
			'body_right'  => $builder_setting_key . '_body_right',
			'bottom_left'   => $builder_setting_key . '_bottom_left',
			'bottom'   => $builder_setting_key . '_bottom',
			'bottom_right'   => $builder_setting_key . '_bottom_right',
		);
		foreach ( $settings as $key ) {
			$wp_customize->add_setting(
				$key,
				array(
					'default'     => onestore_get_default_value( $key ),
					'sanitize_callback' => array( 'OneStore_Customizer_Sanitization', 'multiselect' ),
				)
			);
		}
		$wp_customize->add_control(
			new OneStore_Customize_Control_Builder(
				$wp_customize,
				$item_builder_args['setting_key'],
				array(
					'settings'    => $settings,
					'section'     => $section,
					'label'       => esc_html__( 'Elements & Positions', 'onestore' ),
					'choices'     => $item_builder_args['items'],
					'labels'      => array(
						'top'    => esc_html__( 'Top', 'onestore' ),
						'thumb_left'  => esc_html__( 'Inside Thumbnail - Left', 'onestore' ),
						'thumb'  => esc_html__( 'Inside Thumbnail - Center', 'onestore' ),
						'thumb_right'  => esc_html__( 'Inside Thumbnail - Right', 'onestore' ),
						'thumb_bottom'  => esc_html__( 'Inside Thumbnail - Bottom', 'onestore' ),
						'body_before'  => esc_html__( 'Before Body', 'onestore' ),
						'body_left'  => esc_html__( 'Body - Left', 'onestore' ),
						'body'  => esc_html__( 'Body', 'onestore' ),
						'body_right'  => esc_html__( 'Body - Right', 'onestore' ),
						'bottom_left'   => esc_html__( 'Bottom - Left', 'onestore' ),
						'bottom'   => esc_html__( 'Bottom', 'onestore' ),
						'bottom_right'   => esc_html__( 'Bottom - Right', 'onestore' ),
					),
					'layout'      => 'block',
					'priority'    => $priority + 10,
				)
			)
		);
	}

	/**
	 * Enqueue customizer controls scripts & styles.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'onestore-customize-controls', ONESTORE_CSS_URL . '/admin/customize-controls.css', array(), ONESTORE_VERSION );
		wp_style_add_data( 'onestore-customize-controls', 'rtl', 'replace' );

		wp_enqueue_script( 'onestore-customize-elements', ONESTORE_JS_URL . '/admin/customize-elements.js', array(), ONESTORE_VERSION, true );
		wp_enqueue_script( 'onestore-customize-controls', ONESTORE_JS_URL . '/admin/customize-controls.js', array( 'customize-controls', 'onestore-customize-elements' ), ONESTORE_VERSION, true );

		wp_localize_script(
			'onestore-customize-controls',
			'onestoreCustomizerControlsData',
			array(
				'contexts' => $this->get_control_contexts(),
			)
		);

		// Support preview urls.
		if ( function_exists( 'wc_get_page_permalink' ) ) {

			$shop_url = wc_get_page_permalink( 'shop' );

			self::set_preview_url( 'woocommerce_product_catalog', $shop_url );
			self::set_preview_url( 'woocommerce_cart', wc_get_page_permalink( 'cart' ) );
			self::set_preview_url( 'woocommerce_checkout', wc_get_page_permalink( 'checkout' ) );

			if ( defined( 'SAVP__PATH' ) ) {
				$query = new WP_Query(
					[
						'post_type' => 'product',
						'posts_per_page' => 1,
						// 'orderby' => 'rand',
						'tax_query' => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'product_type',
								'field'    => 'slug',
								'terms'    => array( 'variable' ),
							),
						),
					]
				);

				$posts = $query->get_posts();
				if ( count( $posts ) ) {
					$single_product_variable_link  = get_permalink( $posts[0] );
					self::set_preview_url( 'woocommerce_product_single', $single_product_variable_link );
				}
			} else {
				$products = wc_get_products(
					array(
						'limit' => 1,
					)
				);

				if ( count( $products ) ) {
					$product_link = $products[0]->get_permalink();
					self::set_preview_url( 'woocommerce_product_single', $product_link );
				}
			}
		}

		$blog_url = get_permalink( get_option( 'page_for_posts' ) );
		self::set_preview_url( 'onestore_section_blog_index', $blog_url );
		self::set_preview_url( 'onestore_section_entry_default', $blog_url );
		self::set_preview_url( 'onestore_section_entry_grid', $blog_url );

		self::$preview_urls = apply_filters( 'onestore/customizer/preview_urls', self::$preview_urls );

		wp_localize_script(
			'onestore-customize-controls',
			'onestorePreviewURLs',
			self::$preview_urls
		);
		wp_localize_script(
			'onestore-customize-controls',
			'onestoreTexts',
			array(
				'upgrade_alert' => esc_html__( 'Please upgrade to the Plus version to unlock this feature!', 'onestore' ),
			)
		);
	}

	/**
	 * Enqueue customizer preview scripts & styles.
	 */
	public function enqueue_preview_scripts() {
		wp_enqueue_script( 'onestore-customize-preview', ONESTORE_JS_URL . '/admin/customize-preview.js', array( 'customize-preview' ), ONESTORE_VERSION, true );

		wp_localize_script(
			'onestore-customize-preview',
			'onestoreCustomizerPreviewData',
			array(
				'postMessages' => $this->get_setting_postmessages(),
				'fonts'        => onestore_get_all_fonts(),
			)
		);
	}

	/**
	 * Print <style> tags for preview frame.
	 */
	public function print_preview_styles() {
		// Print global preview CSS.
		echo '<style id="onestore-preview-css" type="text/css">.customize-partial-edit-shortcut button:hover,.customize-partial-edit-shortcut button:focus{border-color: currentColor}</style>' . "\n";

		/**
		 * Print saved theme_mods CSS.
		 */

		$postmessages = $this->get_setting_postmessages();
		$default_values = $this->get_setting_defaults();

		// Loop through each setting.
		foreach ( $postmessages as $key => $rules ) {
			// Get saved value.
			$setting_value = get_theme_mod( $key );

			// Get default value.
			$default_value = onestore_array_value( $default_values, $key );
			if ( is_null( $default_value ) ) {
				$default_value = '';
			}

			// Temporary CSS array to organize output.
			$css_array = array();

			// Add CSS only if value is not the same as default value and not empty.
			if ( '' !== $setting_value ) {
				foreach ( $rules as $rule ) {
					// Check rule validity, and then skip if it's not valid.
					if ( ! $this->check_postmessage_rule_for_css_compatibility( $rule ) ) {
						continue;
					}

					// Sanitize rule.
					$rule = $this->sanitize_postmessage_rule_value( $rule, $setting_value );

					// Add to CSS array.
					$css_array[ $rule['media'] ][ $rule['element'] ][ $rule['property'] ] = $rule['value'];
				}
			}

			echo '<style id="onestore-customize-preview-css-' . $key . '" type="text/css">' . onestore_convert_css_array_to_string( $css_array ) . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Print <script> tags for preview frame.
	 */
	public function print_preview_scripts() {
		?>
		<script type="text/javascript">
			(function() {
				'use strict';

				document.addEventListener( 'DOMContentLoaded', function() {
					if ( 'undefined' !== typeof wp && wp.customize && wp.customize.selectiveRefresh && wp.customize.widgetsPreview && wp.customize.widgetsPreview.WidgetPartial ) {
						wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {
							// Nav Menu
							if ( placement.partial.id.indexOf( 'nav_menu_instance' ) ) {
								window.onestore.initAll();
							}
						} );
					}
				});
			})();
		</script>
		<?php
	}

	/**
	 * ====================================================
	 * Public functions
	 * ====================================================
	 */

	/**
	 * Build CSS string from Customizer's postmessages.
	 *
	 * @param array $postmessages
	 * @param array $defaults
	 * @return string
	 */
	public function convert_postmessages_to_css_string( $postmessages = array(), $defaults = array() ) {
		$css_array = $this->convert_postmessages_to_css_array( $postmessages, $defaults );

		return onestore_convert_css_array_to_string( $css_array );
	}

	/**
	 * Convert Customizer's postmessages array to CSS array.
	 *
	 * @param array $postmessages
	 * @param array $defaults
	 * @return string
	 */
	public function convert_postmessages_to_css_array( $postmessages = array(), $defaults = array() ) {
		// Temporary CSS array to organize output.
		// Media groups are defined now, for proper responsive orders.
		$css_array = array(
			'global' => array(),
			'@media screen and (max-width: 1023px)' => array(),
			'@media screen and (max-width: 499px)' => array(),
		);

		$setting_values = get_theme_mods();
		foreach ( (array) $defaults as $k => $v ) {
			if ( ! isset( $setting_values[ $k ] ) ) {
				$setting_values[ $k ] = $v;
			}
		}

		// Intersect the whole postmessages array with saved theme mods array.
		// This way we can optimize the iteration to only process the existing theme mods.
		$keys = array_intersect( array_keys( $postmessages ), array_keys( $setting_values ) );

		// Loop through each setting.
		foreach ( $keys as $key ) {
			// Get saved value.
			$setting_value = get_theme_mod( $key, onestore_array_value( $defaults, $key ) );

			// Skip this setting if value is not valid (only accepts string and number).
			if ( ! is_numeric( $setting_value ) && ! is_string( $setting_value ) ) {
				continue;
			}

			// Skip this setting if value is empty string.
			if ( '' === $setting_value ) {
				continue;
			}

			// Loop through each rule.
			foreach ( $postmessages[ $key ] as $rule ) {
				// Check rule validity, and then skip if it's not valid.
				if ( ! $this->check_postmessage_rule_for_css_compatibility( $rule ) ) {
					continue;
				}

				// Sanitize rule.
				$rule = $this->sanitize_postmessage_rule_value( $rule, $setting_value );

				// Add to CSS array.
				$css_array[ $rule['media'] ][ $rule['element'] ][ $rule['property'] ] = $rule['value'];
			}
		}

		return $css_array;
	}

	/**
	 * Check a postmessage rule and return whether it's valid or not.
	 *
	 * @param array $rule
	 * @return boolean
	 */
	public function check_postmessage_rule_for_css_compatibility( $rule ) {
		// Check if there is no type defined, then return false.
		if ( ! isset( $rule['type'] ) ) {
			return false;
		}

		// Skip rule if it's not CSS related.
		if ( ! in_array( $rule['type'], array( 'css', 'font' ) ) ) {
			return false;
		}

		// Check if no element selector is defined, then return false.
		if ( ! isset( $rule['element'] ) ) {
			return false;
		}

		// Check if no property is defined, then return false.
		if ( ! isset( $rule['property'] ) || empty( $rule['property'] ) ) {
			return false;
		}

		// Passed all checks, return true.
		return true;
	}

	/**
	 * Sanitize a postmessage rule, run rule function, format original setting value and fill it into the rule.
	 *
	 * @param array $rule
	 * @param mixed $setting_value
	 * @return array
	 */
	public function sanitize_postmessage_rule_value( $rule, $setting_value ) {
		// Declare empty array to hold all available fonts.
		// Will be populated later, only when needed.
		$fonts = array();

		// If "media" attribute is not specified, set it to "global".
		if ( ! isset( $rule['media'] ) || empty( $rule['media'] ) ) {
			$rule['media'] = 'global';
		}

		// If "pattern" attribute is not specified, set it to "$".
		if ( ! isset( $rule['pattern'] ) || empty( $rule['pattern'] ) ) {
			$rule['pattern'] = '$';
		}

		// Check if there is function attached.
		if ( isset( $rule['function'] ) && isset( $rule['function']['name'] ) ) {
			// Apply function to the original value.
			switch ( $rule['function']['name'] ) {
				/**
				 * Explode raw value by space (' ') as the delimiter and then return value from the specified index.
				 *
				 * args[0] = index of exploded array to return
				 */
				case 'explode_value':
					if ( ! isset( $rule['function']['args'][0] ) ) {
						break;
					}

					$index = $rule['function']['args'][0];

					if ( ! is_numeric( $index ) ) {
						break;
					}

					$array = explode( ' ', $setting_value );

					$setting_value = isset( $array[ $index ] ) ? $array[ $index ] : '';
					break;

				/**
				 * Scale all dimensions found in the raw value according to the specified scale amount.
				 *
				 * args[0] = scale amount
				 */
				case 'scale_dimensions':
					if ( ! isset( $rule['function']['args'][0] ) ) {
						break;
					}

					$scale = $rule['function']['args'][0];

					if ( ! is_numeric( $scale ) ) {
						break;
					}

					$parts = explode( ' ', $setting_value );
					$new_parts = array();
					foreach ( $parts as $i => $part ) {
						$number = floatval( $part );
						$unit = str_replace( $number, '', $part );

						$new_parts[ $i ] = ( $number * $scale ) . $unit;
					}

					$setting_value = implode( ' ', $new_parts );
					break;
			}
		}

		// Parse value for "font" type.
		if ( 'font' === $rule['type'] ) {
			$chunks = explode( '|', $setting_value );

			if ( 2 === count( $chunks ) ) {
				// Populate $fonts array if haven't.
				if ( empty( $fonts ) ) {
					$fonts = onestore_get_all_fonts();
				}
				$setting_value = onestore_array_value( $fonts[ $chunks[0] ], $chunks[1], $chunks[1] );
			}
		}

		// Replace any $ found in the pattern to value.
		$rule['value'] = str_replace( '$', $setting_value, $rule['pattern'] );

		// Replace any $ found in the media screen to value.
		$rule['media'] = str_replace( '$', $setting_value, $rule['media'] );

		return $rule;
	}

	/**
	 * Return all customizer default preset value.
	 *
	 * @return array
	 */
	public function get_default_colors() {
		return apply_filters(
			'onestore/dataset/default_colors',
			array(
				'transparent'       => 'rgba(0,0,0,0)',
				'white'             => '#ffffff',
				'black'             => '#000000',
				'accent'            => '#19110b',
				'accent2'           => '#19110b',
				'bg'                => '#f5f5f5',
				'text'              => '#666666',
				'heading'           => '#333333',
				'meta'              => '#bbbbbb',
				'subtitle'            => 'rgba(0,0,0,0.05)',
				'border'            => 'rgba(0,0,0,0.1)',
			)
		);
	}

	/**
	 * Return all customizer setting postmessages.
	 *
	 * @return array
	 */
	public function get_setting_postmessages() {
		if ( ! isset( $GLOBAL['onestore_customize_postmessages'] ) ) {
			self::load_setup();
			$GLOBAL['onestore_customize_postmessages'] = apply_filters( 'onestore/customizer/setting_postmessages', array() );
		}
		return $GLOBAL['onestore_customize_postmessages'];
	}

	/**
	 * Return all customizer setting .
	 *
	 * @return array
	 */
	public function get_control_contexts() {
		if ( ! isset( $GLOBAL['onestore_customize_control_contexts'] ) ) {
			self::load_setup();
			$GLOBAL['onestore_customize_control_contexts'] = apply_filters( 'onestore/customizer/control_contexts', array() );
		}
		return $GLOBAL['onestore_customize_control_contexts'];
	}

	/**
	 * Return all customizer setting defaults.
	 *
	 * @return array
	 */
	public function get_setting_defaults() {

		$defaults = OneStore()->get_settings_defaults();

		return $defaults;
	}

	/**
	 * Return single customizer setting value.
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function get_setting_value( $key, $default = null ) {
		$value = get_theme_mod( $key, null );
		// Fallback to defaults array.
		if ( is_null( $value ) ) {
			$value = onestore_array_value( $this->get_setting_defaults(), $key, null );
		}

		// Fallback to default parameter.
		if ( is_null( $value ) ) {
			$value = $default;
		}

		$value = apply_filters( 'onestore/customizer/setting_value', $value, $key );
		return $value;
	}

	/**
	 * Return all page types for page settings.
	 *
	 * @return array
	 */
	public function get_all_page_settings_types() {
		// Define sections with default page types.
		$page_sections = array(
			'search' => array(
				'title' => esc_html__( 'Search Results Page', 'onestore' ),
			),
			'404' => array(
				'title' => esc_html__( '404 Page', 'onestore' ),
			),
		);

		// Add custom post types to sections.
		$post_types = array_merge(
			array( 'post' ),
			get_post_types(
				array(
					'public'             => true,
					'publicly_queryable' => true,
					'rewrite'            => true,
					'_builtin'           => false,
				),
				'names'
			)
		);

		$ignored_post_types = apply_filters( 'onestore/admin/metabox/page_settings/ignored_post_types', array() );

		$post_types = array_diff( $post_types, $ignored_post_types );

		foreach ( $post_types as $post_type ) {
			$post_type_obj = get_post_type_object( $post_type );

			$page_sections[ $post_type . '_archive' ] = array(
				/* translators: %s: post type's plural name. */
				'title' => sprintf( esc_html__( '%s Archive Page', 'onestore' ), $post_type_obj->labels->name ),
				'description' => sprintf(
					/* translators: %s: post type's plural name. */
					esc_html__( 'These are default settings for main %s Archive page and the taxonomy archive page.', 'onestore' ),
					$post_type_obj->labels->name
				) . '<br><br>' . sprintf(
					/* translators: %s: post type's singular name. */
					esc_html__( 'TIPS: You can specify different settings for each taxonomy via the Page Settings metabox available on the term edit page.', 'onestore' ),
					$post_type_obj->labels->singular_name
				),
			);
			$page_sections[ $post_type . '_singular' ] = array(
				/* translators: %s: post type's singular name. */
				'title' => sprintf( esc_html__( 'Single %s Page', 'onestore' ), $post_type_obj->labels->singular_name ),
				'description' => sprintf(
					/* translators: %s: post type's singular name. */
					esc_html__( 'These are default settings for all Single %s page.', 'onestore' ),
					$post_type_obj->labels->singular_name
				) . '<br><br>' . sprintf(
					/* translators: %s: Post type's singular name. */
					esc_html__( 'TIPS: You can specify different settings for each %s via the Page Settings metabox available on the edit page.', 'onestore' ),
					$post_type_obj->labels->singular_name
				),
			);
		}

		return $page_sections;
	}

	/**
	 * Return all active fonts divided into each provider.
	 *
	 * @param string $group
	 * @return array
	 */
	public function get_active_fonts( $group = null ) {
		$fonts = array(
			'web_safe_fonts' => array(),
			'custom_fonts' => array(),
			'google_fonts' => array(),
		);

		$count = 0;

		$saved_settings = get_theme_mods();
		if ( empty( $saved_settings ) ) {
			$saved_settings = array();
		}

		// Iterate through the saved customizer settings, to find all font family settings.
		foreach ( $saved_settings as $key => $value ) {
			// Check if this setting is not a font family, then skip this setting.
			if ( false === strpos( $key, '_font_family' ) ) {
				continue;
			}

			// Split value format to [font provider, font name].
			$args = explode( '|', $value );

			// Only add if value format is valid.
			if ( 2 === count( $args ) ) {
				// Add to active fonts list.
				// Make sure it is has not been added before.
				if ( ! in_array( $args[1], $fonts[ $args[0] ] ) ) {
					$fonts[ $args[0] ][] = $args[1];
				}

				// Increment counter.
				$count += 1;
			}
		}

		// Check using the counter, if there is no saved settings about font family, add the default system font as active.
		if ( 0 === $count ) {
			$fonts['web_safe_fonts'][] = 'Default System Font';
		}

		// Return values.
		if ( is_null( $group ) ) {
			return $fonts;
		} else {
			return onestore_array_value( $fonts, $group, array() );
		}
	}

	/**
	 * Return Google Fonts embed link from Customizer typography options.
	 *
	 * @return string
	 */
	public function generate_active_google_fonts_embed_url() {
		return onestore_build_google_fonts_embed_url( $this->get_active_fonts( 'google_fonts' ) );
	}
}

OneStore_Customizer::instance();
