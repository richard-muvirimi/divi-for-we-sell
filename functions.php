<?php
/*This file is part of divi-for-we-sell, Divi child theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

function divi_for_we_sell_enqueue_child_styles()
{
	$parent_style = 'parent-style';
	wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
	wp_enqueue_style(
		'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array($parent_style),
		wp_get_theme()->get('Version')
	);
}
add_action('wp_enqueue_scripts', 'divi_for_we_sell_enqueue_child_styles');

/*Write here your own functions */

//add custom page style

function tn_addStackedLayout($layouts)
{

	$layouts['stacked'] =  esc_html__('Stacked', 'Divi');

	return $layouts;
}

add_filter('et_divi_header_style_choices', "tn_addStackedLayout");

//add custom page style css

function tn_addCustomLayoutCss($css)
{

	if ('stacked' === et_get_option('header_style', 'left')) {
		$logo_height =	absint(et_get_option('logo_height', '54'));

		$css .= ".et_header_style_stacked #main-header .logo_container #logo { max-height: " .  esc_html($logo_height  . 'px') . ";  height: " . esc_html($logo_height  . 'px') . "; }";
		$css .= ".et_header_style_stacked #top-header-container .et-search-form { background : " . esc_html(et_get_option('accent_color', '#2ea3f2')) . " !important; }";
	}
	return $css;
}

add_filter('et_divi_theme_customizer_css_output', "tn_addCustomLayoutCss");

//secondary menu items
/**
 * @param \WP_Customize_Manager $wp_customize
 */
function addSecondaryMenuOptions($wp_customize)
{

	$wp_customize->get_setting('et_divi[header_style]')->transport = 'refresh';

	if ('stacked' === et_get_option('header_style', 'left')) {

		$wp_customize->add_setting('et_divi[secondary_nav_condense_side]', array(
			'default' => 'none',
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_condense_side',
		));

		$wp_customize->add_control('et_divi[secondary_nav_condense_side]', array(
			'label' => esc_html__('Condense Items', 'Divi'),
			'section' => 'et_divi_header_secondary',
			'settings' => 'et_divi[secondary_nav_condense_side]',
			'type' => 'select',
			'choices' => array(
				'none' => esc_html__('None', 'Divi'),
				'left' => esc_html__('Left', 'Divi'),
				'right' => esc_html__('Right', 'Divi'),
			)
		));

		$wp_customize->add_setting('et_divi[secondary_nav_hidecart]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'wp_validate_boolean',
		));

		if (class_exists('woocommerce')) {

			$wp_customize->add_control('et_divi[secondary_nav_hidecart]', array(
				'label' => esc_html__('Display Cart', 'Divi'),
				'section' => 'et_divi_header_secondary',
				'type' => 'checkbox',
			));
		}
	}
}

add_action('customize_register', 'addSecondaryMenuOptions', 100);

function sanitize_condense_side($side)
{

	if (in_array($side, array("left", "right", "none"))) {
		return $side;
	}
	return "none";
}

function tn_layout_body_class($classes)
{

	if ('stacked' === et_get_option('header_style', 'left')) {

		$side = et_get_option('secondary_nav_condense_side', "right");

		switch ($side) {
			case "right":
				$classes[] = 'et_condense_right_secondary_nav';
				break;
			case "left":
				$classes[] = 'et_condense_left_secondary_nav';
				break;
			default:
		}

		if (!et_get_option('secondary_nav_hidecart', false)) {
			$classes[] = 'et_hidecart_secondary_nav';
		}
	}
	return $classes;
}

add_filter('body_class', 'tn_layout_body_class', 15);

function tn_show_cart_total($args = array())
{
	if (!class_exists('woocommerce') || !WC()->cart) {
		return;
	}

	$defaults = array(
		'no_text' => false,
	);

	$args = wp_parse_args($args, $defaults);

	$items_number = WC()->cart->get_cart_contents_count();

	$url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : WC()->cart->get_cart_url();

	printf(
		'<a href="%1$s" class="et-cart-info">
    <span>%2$s</span>
    <i id="tn-cart-icon"></i>
</a>',
		esc_url($url),
		(!$args['no_text']
			? esc_html(sprintf(
				_x('Cart (%1$s)', 'WooCommerce items number', 'Divi'),
				number_format_i18n($items_number)
			))
			: '')
	);
}

function divi_for_we_sell_enqueue_child_scripts()
{

	$parent_style = 'customiser-parent-style';
	wp_enqueue_script($parent_style, get_template_directory_uri() . 'js/theme-customizer-controls.js');
	wp_enqueue_script(
		'tn-script',
		get_stylesheet_directory_uri() . '/js/tn_script_customiser.js',
		array($parent_style, 'jquery'),
		wp_get_theme()->get('Version')
	);
}

add_action('customize_controls_enqueue_scripts', 'divi_for_we_sell_enqueue_child_scripts');

function tn_enqueue_scripts()
{

	wp_enqueue_script(
		'tn-script',
		get_stylesheet_directory_uri() . '/js/tn_script.js',
		array('jquery'),
		wp_get_theme()->get('Version')
	);
}

add_action('wp_enqueue_scripts', 'tn_enqueue_scripts');