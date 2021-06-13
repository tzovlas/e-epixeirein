<?php
/**
 * Customizer custom control: Typography
 *
 * @package OneStore
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'OneStore_Customize_Control_CSS' ) ) :
	/**
	 * Typography control class
	 */
	class OneStore_Customize_Control_CSS extends OneStore_Customize_Control {
		/**
		 * @var string
		 */
		public $type = 'onestore-css';

		/**
		 * @var array
		 */
		public $units = array();

		/**
		 * @var array
		 */
		public $choices = array();
		public $states = array();
		public $mapping = array();
		public $devices = array();
		public $hide_groups = array();
		public $disabled_props = array();

		public $responsive = true;
		public function __construct( $manager, $id, $args = array() ) {
			parent::__construct( $manager, $id, $args );
			if ( ! isset( $GLOBALS['onestore_custo_control_css_tpl_added'] ) ) {
				$GLOBALS['onestore_custo_control_css_tpl_added'] = 1;
				add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_popup_template' ) );
			}
		}

		/**
		 * Setup parameters for content rendering by Underscore JS template.
		 */
		public function to_json() {
			parent::to_json();

			$this->json['name'] = $this->id;
			$this->json['inputs'] = array();
			$all_devices = array( 'desktop', 'tablet', 'mobile' );
			$devices = array();
			if ( ! is_array( $this->devices ) ) {
				if ( 'all' == $this->devices ) {
					$devices = array( 'desktop', 'tablet', 'mobile' );
				}
			} else {
				$devices = $this->devices;
			}

			if ( empty( $devices ) ) {
				$devices = array( 'desktop' );
			}

			$id = $this->id;

			$data = array();

			foreach ( $this->settings as $setting_key => $setting ) {
				$setting_id = $setting->id;

				$current_state = '';
				$device = 'desktop';
				$okey = '';
				if ( isset( $this->mapping[ $setting_id ] ) ) {
					$okey = $this->mapping[ $setting_id ]['prop'];
					$device = $this->mapping[ $setting_id ]['device'];
					$current_state = $this->mapping[ $setting_id ]['state'];
				}

				if ( ! $current_state ) {
					$current_state = 'default';
				}
				$value = $this->value( $setting_key );
				$parse_value = array();

				switch ( $okey ) {
					case 'margin':
					case 'padding':
					case 'border_width':
					case 'border_radius':
					case 'outline_width':
							$parse_value = $this->parse_dimensions_unit( $value );
						break;
					case 'box_shadow':
						$parse_value = $this->parse_box_shadow( $value );
						break;
					case 'font_size':
					case 'line_height':
					case 'letter_spacing':
					case 'outline_offset':
					case 'width':
					case 'height':
					case 'min_width':
					case 'max_width':
					case 'min_height':
					case 'max_height':
					case 'opacity':
							$parse_value = $this->parse_number_unit( $value );
						break;
				}
				$link = $this->get_link( $setting_key );
				$parse_value['value'] = $value;
				$parse_value['__link'] = $link;
				$this->json['inputs'][ $setting_key ] = $parse_value;

				if ( ! isset( $data[ $device ] ) ) {
					$data[ $device ] = array();
				}

				if ( ! isset( $data[ $device ][ $current_state ] ) ) {
					$data[ $device ][ $current_state ] = array();
				}

				$data[ $device ][ $current_state ][ $okey ] = $parse_value;
			}

			$this->json['saved_data'] = $data;
			$this->json['devices'] = $devices;

			$this->json['responsive'] = count( $devices ) > 1 ? true : false;
			$this->json['name'] = $this->id;

			if ( empty( $this->states ) || ! is_array( $this->states ) ) {
				$this->states = array( 'default' => 'Default' );
			}

			$this->json['states'] = $this->states;

			$this->json['hide_groups'] = array();
			if ( ! $this->disabled_props || ! is_array( $this->disabled_props ) ) {
				$this->json['disabled_props'] = array();
			} else {
				$this->json['disabled_props'] = $this->disabled_props;
			}

			if ( $this->hide_groups && is_array( $this->hide_groups ) ) {
				$this->json['hide_groups'] = $this->hide_groups;
			}

			$this->json['units'] = array(
				'px' => array(
					'min' => 0,
					'step' => 1,
					'max' => 100,
					'label' => 'px',
				),
				'em' => array(
					'min' => 0,
					'max' => 10,
					'step' => 0.1,
					'label' => 'em',
				),
				'rem' => array(
					'min' => 0,
					'max' => 10,
					'step' => 0.1,
					'label' => 'rem',
				),
				'%' => array(
					'min' => 0,
					'max' => 10,
					'step' => 0.1,
					'label' => '%',
				),
			);

		}

		/**
		 * Convert string to number and unit
		 * example 12px tp 12 and px.
		 */
		protected function parse_number_unit( $value ) {
			$value = trim( $value );
			// Convert raw value string into number and unit.
			$number = '' === $value ? '' : floatval( $value );
			$unit = str_replace( $number, '', $value );
			if ( ! in_array( $unit, [ 'px', 'em', 'rem', '%' ], true ) ) {
				$unit = '';
			}

			return array(
				'number' => $number,
				'unit' => $unit,
			);
		}
		protected function parse_dimensions_unit( $value ) {
			if ( false === $value ) {
				$value = '   '; // 3 empty space for default value
			}

			// Convert dimensions string into numbers and unit.
			$numbers = array();
			$units = [ 'px', 'em', 'rem', '%' ];
			$unit = '';
			$parts = explode( ' ', $value );
			for ( $i = 0; $i < 4; $i ++ ) {
				$part = isset( $parts[ $i ] ) ? $parts[ $i ] : '';
				if ( 'auto' == $part ) {
					$numbers[] = $part;
				} else {
					$_number = '' === $part ? '' : floatval( $part );
					$_unit = str_replace( $_number, '', $part );
					if ( '' !== $_unit ) {
						$unit = $_unit;
					}
					$numbers[] = $_number;

				}
			}

			return array(
				'numbers' => $numbers,
				'unit' => $unit,
				'value' => $value,
			);

		}

		protected function parse_box_shadow( $value ) {
			if ( false === $value || '' === trim( $value ) ) {
				$value = '0 0 0 0 rgba(0,0,0,0)'; // 4 empty space for default value
			}
			$chunks = explode( ' ', $value );
			return array(
				'h_offset' => intval( $chunks[0] ),
				'v_offset' => intval( $chunks[1] ),
				'blur' => intval( $chunks[2] ),
				'spread' => intval( $chunks[3] ),
				'color' => $chunks[4],
			);
		}

		protected function content_template_typo( $device = '' ) {
			?>
			<# var typeLabels = {
			font_family: '<?php esc_html_e( 'Font', 'onestore' ); ?>',
			font_weight: '<?php esc_html_e( 'Weight', 'onestore' ); ?>',
			font_style: '<?php esc_html_e( 'Style', 'onestore' ); ?>',
			text_transform: '<?php esc_html_e( 'Transform', 'onestore' ); ?>',
			font_size: '<?php esc_html_e( 'Size', 'onestore' ); ?>',
			line_height: '<?php esc_html_e( 'Line Height', 'onestore' ); ?>',
			letter_spacing: '<?php esc_html_e( 'Spacing', 'onestore' ); ?>',
			};
			#>
			<div class="customize-control-css-section">
				<div class="css-header"><?php esc_html_e( 'Typography', 'onestore' ); ?></div>
				<div class="css-settings ">

					<div class="customize-control-onestore-typography">

						<# if ( ! data.disabled_props.font_family ) { #>
						<div class="onestore-typography-fieldset onestore-row">
							<label class="onestore-row-item">
								<span class="onestore-small-label">{{ typeLabels.font_family }}</span>
								<select class="onestore-typography-input" {{{ itemData.font_family.__link }}}>
									<option value=""><?php esc_html_e( 'Default', 'onestore' ); ?></option>
									<# _.each( choices.font_family, function( provider_data, provider ) { #>
										<# if ( 0 == provider_data.fonts.length ) return; #>
										<optgroup label="{{ provider_data.label }}">
											<# _.each( provider_data.fonts, function( label, value ) { #>
												<option value="{{ value }}">{{{ label }}}</option>
											<# }); #>
										</optgroup>
									<# }); #>
								</select>
							</label>
						</div>
						<# } #>

						<div class="onestore-typography-fieldset onestore-row">

							<label class="onestore-row-item">
								<span class="onestore-small-label"><?php esc_html_e( 'Font Weight', 'onestore' ); ?></span>
								<select class="onestore-typography-input" {{{ itemData.font_weight.__link }}}>
									<option value=""><?php esc_html_e( 'Default', 'onestore' ); ?></option>
									<# _.each( choices.font_weight, function( label, value ) { #>
										<option value="{{ value }}">{{{ label }}}</option>
									<# }); #>
								</select>
							</label>

							<label class="onestore-row-item">
								<span class="onestore-small-label"><?php esc_html_e( 'Font Style', 'onestore' ); ?></span>
								<select class="onestore-typography-input" {{{ itemData.font_style.__link }}}>
									<option value=""><?php esc_html_e( 'Default', 'onestore' ); ?></option>
									<# _.each( choices.font_style, function( label, value ) { #>
										<option value="{{ value }}">{{{ label }}}</option>
									<# }); #>
								</select>
							</label>
							<label class="onestore-row-item">
								<span class="onestore-small-label"><?php esc_html_e( 'Text Transform', 'onestore' ); ?></span>
								<select class="onestore-typography-input"  {{{ itemData.text_transform.__link }}}>
									<option value=""><?php esc_html_e( 'Default', 'onestore' ); ?></option>
									<# _.each( choices.text_transform, function( label, value ) { #>
										<option value="{{ value }}">{{{ label }}}</option>
									<# }); #>
								</select>
							</label>
						</div>

						<div class="onestore-typography-fieldset onestore-row ">
							<label class="onestore-row-item">
								<span class="onestore-small-label"><?php esc_html_e( 'Size', 'onestore' ); ?></span>
								<span class="onestore-typography-size onestore-row">
									<span class="onestore-row-item">
										<input class="onestore-typography-size-input onestore-input-with-unit" type="number" value="{{ itemData.font_size.number }}" min="" max="" step="" placeholder="<?php esc_attr_e( 'Default', 'onestore' ); ?>">
									</span>
									<span class="onestore-row-item" style="width: 30px;">
										<select class="onestore-typography-size-unit onestore-unit">
											<# _.each( units.font_size, function( unit_data, unit ) { #>
												<option value="{{ unit }}" {{ unit == itemData.font_size.unit ? 'selected="selected"' : '' }} data-min="{{ unit_data.min }}" data-max="{{ unit_data.max }}" data-step="{{ unit_data.step }}">{{{ unit_data.label }}}</option>
											<# }); #>
										</select>
									</span>
									<input type="hidden"  {{{ itemData.font_size.__link }}} class="onestore-typography-size-value" value="" >
								</span>
							</label>
							<label class="onestore-row-item">
								<span class="onestore-small-label"><?php esc_html_e( 'Line Height', 'onestore' ); ?></span>
								<span class="onestore-typography-size onestore-row">
									<span class="onestore-row-item">
										<input class="onestore-typography-size-input onestore-input-with-unit" type="number" value="{{ itemData.line_height.number }}" min="" max="" step="" placeholder="<?php esc_attr_e( 'Default', 'onestore' ); ?>">
									</span>
									<span class="onestore-row-item" style="width: 30px;">
										<select class="onestore-typography-size-unit onestore-unit">
											<# _.each( units.line_height, function( unit_data, unit ) { #>
												<option value="{{ unit }}" {{ unit == itemData.line_height.unit ? 'selected="selected"' : '' }} data-min="{{ unit_data.min }}" data-max="{{ unit_data.max }}" data-step="{{ unit_data.step }}">{{{ unit_data.label }}}</option>
											<# }); #>
										</select>
									</span>
									<input type="hidden" {{{ itemData.line_height.__link }}} class="onestore-typography-size-value" value="" >
								</span>
							</label>
							<label class="onestore-row-item">
								<span class="onestore-small-label"><?php esc_html_e( 'Spacing', 'onestore' ); ?></span>
								<span class="onestore-typography-size onestore-row">
									<span class="onestore-row-item">
										<input class="onestore-typography-size-input onestore-input-with-unit" type="number" value="{{ itemData.letter_spacing.number }}" min="" max="" step="" placeholder="<?php esc_attr_e( 'Default', 'onestore' ); ?>">
									</span>
									<span class="onestore-row-item" style="width: 30px;">
										<select class="onestore-typography-size-unit onestore-unit">
											<# _.each( units.letter_spacing, function( unit_data, unit ) { #>
												<option value="{{ unit }}" {{ unit == itemData.letter_spacing.unit ? 'selected="selected"' : '' }} data-min="{{ unit_data.min }}" data-max="{{ unit_data.max }}" data-step="{{ unit_data.step }}">{{{ unit_data.label }}}</option>
											<# }); #>
										</select>
									</span>
									<input type="hidden" {{{ itemData.letter_spacing.__link }}} class="onestore-typography-size-value" value="" >
								</span>
							</label>
						</div>

					</div>

					<# if ( ! data.disabled_props.color ) { #>
					<div class="css-color customize-control-onestore-color">
						<label class="label"><?php esc_html_e( 'Color', 'onestore' ); ?></label>
						<div class="customize-control-content onestore-colorpicker onestore-colorpicker-with-alpha">
							<input value="{{ itemData.color.value }}"  type="text" maxlength="30" class="color-picker" data-palette="" placeholder="<?php esc_attr_e( 'Hex / RGBA', 'onestore' ); ?>" data-default-color="" data-show-opacity="true">
							<input value="{{ itemData.color.value }}" {{{ itemData.color.__link }}} type="hidden" class="color-picker-val">
						</div>
					</div>
					<# } #>


				</div>

			</div>
			<?php
		}
		protected function content_template_spacings( $device = '' ) {
			?>
			<div class="customize-control-css-section">
				<div class="css-header"><?php esc_html_e( 'Spacings', 'onestore' ); ?></div>
				<div class="css-settings">

					<# if ( ! data.disabled_props.padding ) { #>
					<div class="customize-control-onestore-dimensions">
						<label class="label"><?php esc_html_e( 'Padding', 'onestore' ); ?></label>
						<div class="onestore-dimensions-fieldset onestore-row" data-linked="false">
							<label class="onestore-row-item" style="width: 30px;">
								<span class="onestore-small-label">&nbsp;</span>
								<button type="button" class="onestore-dimensions-link button button-secondary dashicons dashicons-editor-unlink" tabindex="0"></button>
								<button type="button" class="onestore-dimensions-unlink button button-primary dashicons dashicons-admin-links" tabindex="0"></button>
							</label>
							<# _.each( [ 'top', 'right', 'bottom', 'left' ], function( prop, i ) { #>
								<label class="onestore-row-item">
									<span class="onestore-small-label">{{{ prop }}}</span>
									<input class="onestore-dimensions-input" type="number" value="{{ itemData.padding.numbers[i] }}" min="">
								</label>
							<# }); #>
							<label class="onestore-row-item" style="width: 30px;">
								<span class="onestore-small-label">&nbsp;</span>
								<select class="onestore-dimensions-unit onestore-unit">
									<# _.each( spacingUnits, function( unit_data, unit ) { #>
										<option value="{{ unit }}" {{ unit == itemData.padding.unit ? 'selected="selected"' : '' }} data-min="{{ unit_data.min }}" data-max="{{ unit_data.max }}" data-step="{{ unit_data.step }}">{{{ unit_data.label }}}</option>
									<# }); #>
								</select>
							</label>

							<input type="hidden" {{{ itemData.padding.__link }}} class="onestore-dimensions-value" value="">
						</div>
					</div>
					<# } #>

					<# if ( ! data.disabled_props.margin ) { #>
					<div class="customize-control-onestore-dimensions">
						<label class="label"><?php esc_html_e( 'Margin', 'onestore' ); ?></label>
						<div class="onestore-dimensions-fieldset onestore-row" data-linked="false">
							<label class="onestore-row-item" style="width: 30px;">
								<span class="onestore-small-label">&nbsp;</span>
								<button type="button" class="onestore-dimensions-link button button-secondary dashicons dashicons-editor-unlink" tabindex="0"></button>
								<button type="button" class="onestore-dimensions-unlink button button-primary dashicons dashicons-admin-links" tabindex="0"></button>
							</label>
							<# _.each( [ 'top', 'right', 'bottom', 'left' ], function( prop, i ) { #>
								<label class="onestore-row-item">
									<span class="onestore-small-label">{{{ prop }}}</span>
									<input class="onestore-dimensions-input" type="number" value="{{ itemData.margin.numbers[i] }}" min="">
								</label>
							<# }); #>
							<label class="onestore-row-item" style="width: 30px;">
								<span class="onestore-small-label">&nbsp;</span>
								<select class="onestore-dimensions-unit onestore-unit">
									<# _.each( spacingUnits, function( unit_data, unit ) { #>
										<option value="{{ unit }}" {{ unit == itemData.margin.unit ? 'selected="selected"' : '' }} data-min="{{ unit_data.min }}" data-max="{{ unit_data.max }}" data-step="{{ unit_data.step }}">{{{ unit_data.label }}}</option>
									<# }); #>
								</select>
							</label>

							<input type="hidden" {{{ itemData.margin.__link }}} data-empty-auto="true" class="onestore-dimensions-value" value="">
						</div>
					</div>
					<# } #>

				</div>
			</div>
			<?php
		}
		protected function content_template_border( $device = '' ) {
			?>
			<div class="customize-control-css-section">
				<div class="css-header"><?php esc_html_e( 'Border', 'onestore' ); ?></div>
				<div class="css-settings">

					<div class="customize-control-onestore-dimensions">
						<label class="label"><?php esc_html_e( 'Border width', 'onestore' ); ?></label>
						<div class="onestore-dimensions-fieldset onestore-row" data-linked="false">
							<label class="onestore-row-item" style="width: 30px;">
								<span class="onestore-small-label">&nbsp;</span>
								<button type="button" class="onestore-dimensions-link button button-secondary dashicons dashicons-editor-unlink" tabindex="0"></button>
								<button type="button" class="onestore-dimensions-unlink button button-primary dashicons dashicons-admin-links" tabindex="0"></button>
							</label>
							<# _.each( [ 'top', 'right', 'bottom', 'left' ], function( prop, i ) { #>
								<label class="onestore-row-item">
									<span class="onestore-small-label">{{{ prop }}}</span>
									<input class="onestore-dimensions-input" type="number" value="{{ itemData.border_width.numbers[i] }}" min="">
								</label>
							<# }); #>
							<label class="onestore-row-item" style="width: 30px;">
								<span class="onestore-small-label">&nbsp;</span>
								<select class="onestore-dimensions-unit onestore-unit">
									<# _.each( data.units, function( unit_data, unit ) { #>
										<option value="{{ unit }}" {{ unit == itemData.border_width.unit ? 'selected="selected"' : '' }} data-min="{{ unit_data.min }}" data-max="{{ unit_data.max }}" data-step="{{ unit_data.step }}">{{{ unit_data.label }}}</option>
									<# }); #>
								</select>
							</label>

							<input type="hidden" {{{ itemData.border_width.__link }}}  class="onestore-dimensions-value" value="">
						</div>
					</div>

					<div class="css-field-wrapper">
						<label class="label"><?php esc_html_e( 'Border Style', 'onestore' ); ?></label>
						<select {{{ itemData.border_style.__link }}} >
							<option value="">---</option>
							<option value="none">none</option>
							<option value="hidden">hidden</option>
							<option value="dotted">dotted</option>
							<option value="dashed">dashed</option>
							<option value="solid">solid</option>
							<option value="groove">groove</option>
							<option value="double">double</option>
							<option value="ridge">ridge</option>
							<option value="inset">inset</option>
							<option value="inset">outset</option>
						</select>
					</div>

					<div class="css-color customize-control-onestore-color">
						<label class="label"><?php esc_html_e( 'Color', 'onestore' ); ?></label>
						<div class="customize-control-content onestore-colorpicker onestore-colorpicker-with-alpha">
							<input {{{ itemData.border_color.__link }}} value="{{{ itemData.border_color.value }}}" type="text" maxlength="30" class="color-picker" data-palette="" placeholder="<?php esc_attr_e( 'Hex / RGBA', 'onestore' ); ?>" data-default-color="" data-show-opacity="true">
							<input value="{{ itemData.border_color.value }}" {{{ itemData.border_color.__link }}} type="hidden" class="color-picker-val">
						</div>
					</div>

					<div class="customize-control-onestore-dimensions">
						<label class="label"><?php esc_html_e( 'Border Radius', 'onestore' ); ?></label>
						<div class="onestore-dimensions-fieldset onestore-row" data-linked="false">
							<label class="onestore-row-item" style="width: 30px;">
								<span class="onestore-small-label">&nbsp;</span>
								<button type="button" class="onestore-dimensions-link button button-secondary dashicons dashicons-editor-unlink" tabindex="0"></button>
								<button type="button" class="onestore-dimensions-unlink button button-primary dashicons dashicons-admin-links" tabindex="0"></button>
							</label>
							<# _.each( [ 'top', 'right', 'bottom', 'left' ], function( prop, i ) { #>
								<label class="onestore-row-item">
									<span class="onestore-small-label">{{{ prop }}}</span>
									<input class="onestore-dimensions-input" type="number" value="{{ itemData.border_radius.numbers[i] }}" min="">
								</label>
							<# }); #>
							<label class="onestore-row-item" style="width: 30px;">
								<span class="onestore-small-label">&nbsp;</span>
								<select class="onestore-dimensions-unit onestore-unit">
									<# _.each( spacingUnits, function( unit_data, unit ) { #>
										<option value="{{ unit }}" {{ unit == itemData.border_radius.unit ? 'selected="selected"' : '' }} data-min="{{ unit_data.min }}" data-max="{{ unit_data.max }}" data-step="{{ unit_data.step }}">{{{ unit_data.label }}}</option>
									<# }); #>
								</select>
							</label>

							<input type="hidden" {{{ itemData.border_radius.__link }}} class="onestore-dimensions-value" value="">
						</div>
					</div>


				</div>
			</div>
			<?php
		}

		protected function content_template_outline() {
			?>
			<div class="customize-control-css-section">
				<div class="css-header"><?php esc_html_e( 'Outline', 'onestore' ); ?></div>
				<div class="css-settings">

					<div class="customize-control-onestore-dimensions">
						<label class="label"><?php esc_html_e( 'Ouline width', 'onestore' ); ?></label>
						<div class="onestore-dimensions-fieldset onestore-row" data-linked="false">
							<label class="onestore-row-item" style="width: 30px;">
								<span class="onestore-small-label">&nbsp;</span>
								<button type="button" class="onestore-dimensions-link button button-secondary dashicons dashicons-editor-unlink" tabindex="0"></button>
								<button type="button" class="onestore-dimensions-unlink button button-primary dashicons dashicons-admin-links" tabindex="0"></button>
							</label>
							<# _.each( [ 'top', 'right', 'bottom', 'left' ], function( prop, i ) { #>
								<label class="onestore-row-item">
									<span class="onestore-small-label">{{{ prop }}}</span>
									<input class="onestore-dimensions-input" type="number" value="{{ itemData.outline_width.numbers[i] }}" min="">
								</label>
							<# }); #>
							<label class="onestore-row-item" style="width: 30px;">
								<span class="onestore-small-label">&nbsp;</span>
								<select class="onestore-dimensions-unit onestore-unit">
									<# _.each( data.units, function( unit_data, unit ) { #>
										<option value="{{ unit }}" {{ unit == itemData.outline_width.unit ? 'selected="selected"' : '' }} data-min="{{ unit_data.min }}" data-max="{{ unit_data.max }}" data-step="{{ unit_data.step }}">{{{ unit_data.label }}}</option>
									<# }); #>
								</select>
							</label>
							<input type="hidden" {{{ itemData.outline_width.__link }}}  class="onestore-dimensions-value" value="">
						</div>
					</div>

					<div class="css-field-wrapper">
						<label class="label"><?php esc_html_e( 'Border Style', 'onestore' ); ?></label>
						<select {{{ itemData.outline_style.__link }}} >
							<option value="">---</option>
							<option value="none">none</option>
							<option value="hidden">hidden</option>
							<option value="dotted">dotted</option>
							<option value="dashed">dashed</option>
							<option value="solid">solid</option>
							<option value="groove">groove</option>
							<option value="double">double</option>
							<option value="ridge">ridge</option>
							<option value="inset">inset</option>
							<option value="inset">outset</option>
						</select>
					</div>

					<div class="css-color customize-control-onestore-color">
						<label class="label"><?php esc_html_e( 'Color', 'onestore' ); ?></label>
						<div class="customize-control-content onestore-colorpicker onestore-colorpicker-with-alpha">
							<input {{{ itemData.outline_color.__link }}} value="{{{ itemData.outline_color.value }}}" type="text" maxlength="30" class="color-picker" data-palette="" placeholder="<?php esc_attr_e( 'Hex / RGBA', 'onestore' ); ?>" data-default-color="" data-show-opacity="true">
							<input value="{{ itemData.outline_color.value }}" {{{ itemData.outline_color.__link }}} type="hidden" class="color-picker-val">
						</div>
					</div>

					<div class="customize-control-onestore-slider">
						<label class="label"><?php esc_html_e( 'Offset', 'onestore' ); ?></label>
						<div class="onestore-slider-fieldset onestore-row">
							<div class="onestore-row-item" style="width: 100%;">
								<div class="onestore-slider-ui"></div>
							</div>
							<div class="onestore-row-item" style="width: 50px;">
								<input class="onestore-slider-input onestore-input-with-unit" value="{{ itemData.outline_offset.number }}" type="number" min="0" max="1500" step="1">
							</div>
							<div class="onestore-row-item" style="width: 30px;">
								<select class="onestore-slider-unit onestore-unit">
								<# _.each( sizeUnits, function( unit_data, unit ) { #>
										<option value="{{ unit }}" {{ unit == itemData.outline_offset.unit ? 'selected' : '' }} data-min="{{ unit_data.min }}" data-max="{{ unit_data.max }}" data-step="{{ unit_data.step }}">{{{ unit_data.label }}}</option>
									<# }); #>
								</select>
							</div>
							<input type="hidden" {{{ itemData.outline_offset.__link }}} class="onestore-slider-value" value="">
						</div>
					</div>


				</div>
			</div>
			<?php
		}

		protected function content_template_shadow( $device = '' ) {
			$labels = array(
				'h_offset' => esc_html__( 'H-Offset', 'onestore' ),
				'v_offset' => esc_html__( 'V-Offset', 'onestore' ),
				'blur' => esc_html__( 'Blur', 'onestore' ),
				'spread' => esc_html__( 'Spread', 'onestore' ),
			);

			?>
			<div class="customize-control-css-section">
				<div class="css-header"><?php esc_html_e( 'Shadow', 'onestore' ); ?></div>
				<div class="css-settings customize-control-onestore-shadow">

					<div class="customize-control-content onestore-shadow-fieldset">
						<div class="onestore-row onestore-shadow-row">
							<# var labels = <?php echo json_encode( $labels ); ?>; #>
							<# _.each( labels, function( label, prop ) { #>
								<label class="onestore-row-item onestore-shadow-{{ prop }}">
									<span class="onestore-small-label">{{{ label }}}</span>
									<input type="number" value="{{ itemData.box_shadow[prop] }}" class="onestore-shadow-input" step="1">
								</label>
							<# }); #>
							<label class="onestore-row-item" style="width: 30px; vertical-align: top; padding-left: 10px;">
								<span class="onestore-small-label"><?php esc_html_e( 'Color', 'onestore' ); ?></span>
							</label>
						</div>
						<div class="onestore-shadow-color">
							<input value="{{ itemData.box_shadow.color }}" type="text" maxlength="30" class="onestore-shadow-input color-picker" data-palette="" placeholder="<?php esc_attr_e( 'Hex / RGBA', 'onestore' ); ?>" data-default-color="rgba(0,0,0,0)" data-show-opacity="true">
						</div>
						<input type="hidden" value="{{ itemData.box_shadow.value }}" {{{ itemData.box_shadow.__link }}} class="onestore-shadow-value">
					</div>

				</div>
			</div>
			<?php
		}
		protected function content_template_background( $device = '' ) {
			?>
			<div class="customize-control-css-section">
				<div class="css-header"><?php esc_html_e( 'Background', 'onestore' ); ?></div>
				<div class="css-settings">

					<# if ( ! data.disabled_props.background_image ) { #>
					<div class="css-type-media {{ itemData.background_image.value ? 'has-media' : '' }}">
						<button type="button" class="upload-button button-add-media"><?php esc_html_e( 'Select image', 'onestore' ); ?></button>
						<input type="hidden" {{{ itemData.background_image.__link }}} class="media-value"/>
						<div class="media-preview">
							<# if( itemData.background_image.value ) { #>
								<img src="{{ itemData.background_image.value }}" alt=""/>
							<# } #>
						</div>
						<div class="css-media-actions">
							<button class="media-remove button-secondary" type="button"><?php esc_html_e( 'Remove', 'onestore' ); ?></button>
						</div>
					</div>
					<# } #>

					<# if ( ! data.disabled_props.background_color ) { #>
					<div class="css-color customize-control-onestore-color">
						<label class="label"><?php esc_html_e( 'Color', 'onestore' ); ?></label>
						<div class="customize-control-content onestore-colorpicker onestore-colorpicker-with-alpha">
							<input value="{{ itemData.background_color.value }}"  type="text" maxlength="30" class="color-picker" data-palette="" placeholder="<?php esc_attr_e( 'Hex / RGBA', 'onestore' ); ?>" data-default-color="" data-show-opacity="true">
							<input value="{{ itemData.background_color.value }}" {{{ itemData.background_color.__link }}} type="hidden" class="color-picker-val">
						</div>
					</div>
					<# } #>

					<# if ( ! data.disabled_props.background_position ) { #>
					<div class="half">
						<label class="label"><?php esc_html_e( 'Position', 'onestore' ); ?></label>
						<select {{{ itemData.background_position.__link }}}>
							<option value="">---</option>
							<option value="top left">top left</option>
							<option value="top right">top right</option>
							<option value="top center">top center</option>
							<option value="center">center</option>
							<option value="bottom left">bottom left</option>
							<option value="bottom right">bottom right</option>
							<option value="bottom center">bottom center</option>
						</select>
					</div>
					<# } #>
					
					<# if ( ! data.disabled_props.background_repeat ) { #>
					<div class="half">
						<label class="label"><?php esc_html_e( 'Repeat', 'onestore' ); ?></label>
						<select {{{ itemData.background_repeat.__link }}}>
							<option value="">---</option>
							<option value="no-repeat">no-repeat</option>
							<option value="repeat-x">repeat-x</option>
							<option value="repeat-y">repeat-y</option>
						</select>
					</div>
					<# } #>

					<# if ( ! data.disabled_props.background_size ) { #>
					<div class="half">
						<label class="label"><?php esc_html_e( 'Size', 'onestore' ); ?></label>
						<select {{{ itemData.background_size.__link }}}>
							<option value="">---</option>
							<option value="cover">cover</option>
							<option value="contain">contain</option>
							<option value="auto">auto</option>
						</select>
					</div>
					<# } #>

					<# if ( ! data.disabled_props.background_attachment ) { #>
					<div class="half">
						<label class="label"><?php esc_html_e( 'Attachment', 'onestore' ); ?></label>
						<select {{{ itemData.background_attachment.__link }}}>
							<option value="">---</option>
							<option value="scroll">scroll</option>
							<option value="fixed">fixed</option>
							<option value="local">local</option>
						</select>
					</div>
					<# } #>


				</div>
			</div>
			<?php
		}
		protected function content_template_sizing( $device = '' ) {

			$properties  = array(
				'width' => esc_html__( 'Width', 'onestore' ),
				'min_width' => esc_html__( 'Min Width', 'onestore' ),
				'max_width' => esc_html__( 'Max Width', 'onestore' ),
				'height' => esc_html__( 'Height', 'onestore' ),
				'min_height' => esc_html__( 'Min Height', 'onestore' ),
				'max_height' => esc_html__( 'Max Height', 'onestore' ),
			);
			?>
			<div class="customize-control-css-section">
				<div class="css-header"><?php esc_html_e( 'Measures', 'onestore' ); ?></div>
				<div class="css-settings">

					<?php foreach ( $properties as $key => $label ) { ?>
						<# if ( ! data.disabled_props.<?php echo $key; // WPCS: XSS ok. ?> ) { #>
						<div class="customize-control-onestore-slider">
							<label class="label"><?php echo $label; // WPCS: XSS ok. ?></label>
							<div class="onestore-slider-fieldset onestore-row">
								<div class="onestore-row-item" style="width: 100%;">
									<div class="onestore-slider-ui"></div>
								</div>
								<div class="onestore-row-item" style="width: 50px;">
									<input class="onestore-slider-input onestore-input-with-unit" value="{{ itemData.<?php echo $key; // WPCS: XSS ok. ?>.number }}" type="number" min="0" max="1500" step="1">
								</div>
								<div class="onestore-row-item" style="width: 30px;">
									<select class="onestore-slider-unit onestore-unit">
									<# _.each( sizeUnits, function( unit_data, unit ) { #>
											<option value="{{ unit }}" {{ unit == itemData.<?php echo $key; // WPCS: XSS ok. ?>.unit ? 'selected' : '' }} data-min="{{ unit_data.min }}" data-max="{{ unit_data.max }}" data-step="{{ unit_data.step }}">{{{ unit_data.label }}}</option>
										<# }); #>
									</select>
								</div>
								<input type="hidden" {{{ itemData.<?php echo $key; // WPCS: XSS ok. ?>.__link }}} class="onestore-slider-value" value="">
							</div>
						</div>
						<# } #>
					<?php } ?>
				</div>
			</div>
			<?php
		}

		protected function content_template_extra( $device = '' ) {
			?>
			<div class="customize-control-css-section">
				<div class="css-header"><?php esc_html_e( 'Extra', 'onestore' ); ?></div>
				<div class="css-settings">

					<# if ( ! data.disabled_props.display ) { #>
					<div  class="half">
						<label class="label"><?php esc_html_e( 'Display', 'onestore' ); ?></label>
						<select {{{ itemData.display.__link }}}>
						<option value="">---</option>
							<option value="block">block</option>
							<option value="flex">flex</option>
							<option value="inline-block">inline block</option>
							<option value="inline-flex">inline flex</option>
							<option value="none">none</option>
						</select>
					</div>
					<# } #>

					<# if ( ! data.disabled_props.float ) { #>
					<div  class="half">
						<label class="label"><?php esc_html_e( 'Float', 'onestore' ); ?></label>
						<select {{{ itemData.float.__link }}}>
							<option value="">---</option>
							<option value="none">none</option>
							<option value="left">left</option>
							<option value="right">right</option>
						</select>
					</div>
					<# } #>	

					<# if ( ! data.disabled_props.visibility ) { #>
					<div  class="half">
						<label class="label"><?php esc_html_e( 'Clear', 'onestore' ); ?></label>
						<select {{{ itemData.clear.__link }}}>
							<option value="">---</option>
							<option value="both">both</option>
							<option value="none">none</option>
						</select>
					</div>
					<# } #>

					<# if ( ! data.disabled_props.visibility ) { #>
					<div class="half">
						<label class="label"><?php esc_html_e( 'Visibility', 'onestore' ); ?></label>
						<select {{{ itemData.visibility.__link }}}>
							<option value="">---</option>
							<option value="visible">visible</option>
							<option value="hidden">hidden</option>
						</select>
					</div>
					<# } #>

					<# if ( ! data.disabled_props.opacity ) { #>
					<div class="customize-control-onestore-slider">
						<label class="label"><?php esc_html_e( 'Opacity', 'onestore' ); ?></label>
						<div class="onestore-slider-fieldset onestore-row">
							<div class="onestore-row-item" style="width: 100%;">
								<div class="onestore-slider-ui"></div>
							</div>
							<div class="onestore-row-item" style="width: 50px;">
								<input class="onestore-slider-input" type="number" value="{{ itemData.opacity.number }}" min="0" max="1" step="0.1">
							</div>
							<input type="hidden" class="onestore-slider-value" {{{ itemData.opacity.__link }}} value="">
						</div>
					</div>
					<# } #>

					<# if ( ! data.disabled_props.align_items ) { #>
					<div class="half">
						<label class="label"><?php esc_html_e( 'Flex Vetical', 'onestore' ); ?></label>
						<select {{{ itemData.align_items.__link }}}>
							<option value="">---</option>
							<option value="start">top</option>
							<option value="center">center</option>
							<option value="end">bottom</option>
							<option value="stretch">stretch</option>
						</select>
					</div>
					<# } #>

					<# if ( ! data.disabled_props.justify_content ) { #>
					<div class="half">
						<label class="label"><?php esc_html_e( 'Flex Horizontal', 'onestore' ); ?></label>
						<select {{{ itemData.justify_content.__link }}}>
							<option value="">---</option>
							<option value="start">start</option>
							<option value="center">center</option>
							<option value="end">bottom</option>
							<option value="space-between">space-between</option>
							<option value="space-around">space-around</option>
						</select>
					</div>
					<# } #>

					<# if ( ! data.disabled_props.text_align ) { #>
					<div >
						<label class="label"><?php esc_html_e( 'Text Align', 'onestore' ); ?></label>
						<select {{{ itemData.text_align.__link }}}>
							<option value="">---</option>
							<option value="left">left</option>
							<option value="center">center</option>
							<option value="right">right</option>
						</select>
					</div>
					<# } #>

				</div>
			</div>
			<?php
		}

		/**
		 * Render Underscore JS template for this control's content.
		 */
		protected function content_template() {
			$device = '';
			?>
		<# 
		
		if ( data.label ) { #>
			<span class="customize-control-title {{ data.responsive ? 'onestore-responsive-title' : '' }}">
				{{{ data.label }}}
				<button type="button" class="css-toggle-settings">
					<span class="dashicons dashicons-edit"></span>
				</button>
			</span>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>
			<?php
		}

		protected function popup_template() {
			?>
			<# 
			var stateLength =  Object.keys( data.states ).length;
			var currentDevice =  data._currentDevice || 'desktop';
			#>
			<div class="css-popup-settings {{ stateLength > 1 ? 'more-states' : 'only-state' }}">
				
				<# if ( data.responsive ) { #>
					<div class="css-popup-header onestore-responsive-switcher">
					<# _.each( data.devices, function( device, deviceIndex ) { #>
						<span class="onestore-responsive-switcher-button preview-{{ device }}  {{ currentDevice === device ? 'active' : '' }}" data-device="{{ device }}"><span class="dashicons dashicons-{{ 'mobile' === device ? 'smartphone' : device }}"></span></span>
					<# }); #>
					</div>
				<# } #>
				<#
					var choices = <?php echo json_encode( $this->get_choices() ); ?>,
						units = <?php echo json_encode( $this->get_units() ); ?>;
						sizeUnits = <?php echo json_encode( $this->get_size_units() ); ?>;
						spacingUnits = <?php echo json_encode( $this->get_spacing_units() ); ?>;
				#>

				<# _.each( data.saved_data, function( deviceData, device ) { #>
				<div class="customize-control-content  {{ data.responsive ? 'onestore-responsive-fieldset' : '' }} {{ currentDevice === device ? 'active' : '' }} {{ 'preview-' + device }}">
					<# if ( stateLength > 1 ) { #>
					<div class="css-states-switcher">
					<# _.each( data.states, function( label, state ) { #>
						<span  class="{{ state === 'default' ? 'active' : '' }} "data-state="{{ state }}" href="#">{{ label }}</span>
					<# }); #>
					</div>
					<# } #>

					<# _.each( data.states, function( label, state ) { #>
						<# var itemData = deviceData[ state ]; #>
						<div class="css-state-settings for-state-{{ state }} state-{{ state === 'default' ? 'active' : 'hide' }}">
						
						<# if ( ! data.hide_groups.typo ) { #>
						<?php $this->content_template_typo(); ?>
						<# } #>

						<# if ( ! data.hide_groups.spacings ) { #>
						<?php $this->content_template_spacings(); ?>
						<# } #>

						<# if ( ! data.hide_groups.background ) { #>
						<?php $this->content_template_background(); ?>
						<# } #>

						<# if ( ! data.hide_groups.border ) { #>
						<?php $this->content_template_border(); ?>
						<# } #>

						<# if ( ! data.hide_groups.outline ) { #>
						<?php $this->content_template_outline(); ?>
						<# } #>

						<# if ( ! data.hide_groups.shadow ) { #>
						<?php $this->content_template_shadow(); ?>
						<# } #>

						<# if ( ! data.hide_groups.sizing ) { #>
						<?php $this->content_template_sizing(); ?>
						<# } #>

						<# if ( ! data.hide_groups.extra ) { #>
						<?php $this->content_template_extra(); ?>
						<# } #>

						</div>
					<# }); #>
				</div>
				<# }); #>


				</div>
			<?php
		}


		public function print_popup_template() {
			?>
			<script type="text/html" id="tmpl-customize-control-<?php echo $this->type; ?>-popup-settings">
				<?php $this->popup_template(); ?>
			</script>
			<?php
		}


		/**
		 * Return available choices for this control inputs.
		 *
		 * @param string $key
		 * @return array
		 */
		public function get_choices( $key = null ) {
			$font_families = array();

			foreach ( onestore_get_all_fonts() as $provider => $fonts ) {
				$font_families[ $provider ]['label'] = ucwords( str_replace( '_', ' ', $provider ) );
				$font_families[ $provider ]['fonts'] = array();

				foreach ( $fonts as $name => $stack ) {
					$font_families[ $provider ]['fonts'][ esc_attr( $provider . '|' . $name ) ] = esc_attr( $name );
				}
			}

			$choices = array(
				'font_family' => $font_families,
				'font_weight' => array(
					'100' => esc_html__( 'Thin', 'onestore' ),
					'200' => esc_html__( 'Extra Light', 'onestore' ),
					'300' => esc_html__( 'Light', 'onestore' ),
					'400' => esc_html__( 'Regular', 'onestore' ),
					'500' => esc_html__( 'Medium', 'onestore' ),
					'600' => esc_html__( 'Semi Bold', 'onestore' ),
					'700' => esc_html__( 'Bold', 'onestore' ),
					'800' => esc_html__( 'Extra Bold', 'onestore' ),
					'900' => esc_html__( 'Black', 'onestore' ),
				),
				'font_style' => array(
					'normal' => esc_html__( 'Normal', 'onestore' ),
					'italic' => esc_html__( 'Italic', 'onestore' ),
				),
				'text_transform' => array(
					'none'       => esc_html__( 'None', 'onestore' ),
					'uppercase'  => esc_html__( 'Uppercase', 'onestore' ),
					'lowercase'  => esc_html__( 'Lowercase', 'onestore' ),
					'capitalize' => esc_html__( 'Capitalize', 'onestore' ),
				),
			);

			if ( ! empty( $key ) ) {
				return onestore_array_value( $choices, $key, array() );
			} else {
				return $choices;
			}
		}

		/**
		 * Return available units for this control inputs.
		 *
		 * @param string $key
		 * @return array
		 */
		public function get_units( $key = null ) {
			$units = array(
				'font_size' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
						'label' => 'px',
					),
					'em' => array(
						'min' => 0,
						'max' => 15,
						'step' => 0.1,
						'label' => 'em',
					),
					'rem' => array(
						'min' => 0,
						'max' => 15,
						'step' => 0.1,
						'label' => 'rem',
					),
					'%' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
						'label' => '%',
					),
				),
				'line_height' => array(
					'' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
						'label' => 'em',
					),
					'px' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
						'label' => 'px',
					),
				),
				'letter_spacing' => array(
					'px' => array(
						'min' => -20,
						'max' => 20,
						'step' => 0.1,
						'label' => 'px',
					),
					'em' => array(
						'min' => -2,
						'max' => 2,
						'step' => 0.1,
						'label' => 'em',
					),
				),
			);

			if ( ! empty( $key ) ) {
				return onestore_array_value( $units, $key, array() );
			} else {
				return $units;
			}
		}
		public function get_size_units() {
			$units = array(
				'px' => array(
					'min' => 0,
					'max' => 1500,
					'step' => 1,
					'label' => 'px',
				),
				'em' => array(
					'min' => 0,
					'max' => 50,
					'step' => 0.1,
					'label' => 'em',
				),
				'rem' => array(
					'min' =>  0,
					'max' => 50,
					'step' => 0.1,
					'label' => 'rem',
				),
				'%' => array(
					'min' => 0,
					'max' => 100,
					'step' => 1,
					'label' => '%',
				),
			);

			return $units;
		}

		public function get_spacing_units() {
			$units = array(
				'px' => array(
					'min' => -200,
					'max' => 200,
					'step' => 1,
					'label' => 'px',
				),
				'em' => array(
					'min' => -20,
					'max' => 20,
					'step' => 0.1,
					'label' => 'em',
				),
				'rem' => array(
					'min' => -20,
					'max' => 20,
					'step' => 0.1,
					'label' => 'rem',
				),
				'%' => array(
					'min' => -100,
					'max' => 100,
					'step' => 1,
					'label' => '%',
				),
			);
			return $units;
		}


		public function enqueue() {
			wp_enqueue_script( 'alpha-color-picker' );
			wp_enqueue_style( 'alpha-color-picker' );
		}

	}

	// Register control type.
	$wp_customize->register_control_type( 'OneStore_Customize_Control_CSS' );
endif;
