<?php
/**
 * Plugin Name:     Lightning G3 Expand Widget Areas
 * Plugin URI:      https://github.com/vektor-inc/lightning-g3-expand-widget-areas
 * Description:     Add Widget Area for Lightning G3 mode
 * Author:          Vektor,Inc.
 * Author URI:
 * Text Domain:     lightning-g3-expand-widget-areas
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Lightning_G3_Expand_Widget_Areas
 */

$data = get_file_data(
	__FILE__,
	array(
		'version'    => 'Version',
		'textdomain' => 'Text Domain',
	)
);
define( 'LTG3_EXPAND_WIDGET_AREAS_VERSION', $data['version'] );
define( 'LTG3_EXPAND_WIDGET_AREAS_TEXTDOMAIN', $data['textdomain'] );

function ltg3exwa_add_widget_area() {
	if ( 'g3' === get_option( 'lightning_theme_generation' ) && 'lightning' === get_template() ) {
		$widget_area_array = ltg3exwa_widget_area_array();
		foreach ( $widget_area_array as $hookpoint => $widget_area_single ) {
			add_action(
				$hookpoint,
				function( $hookpoint ) {
					$widget_area_array = ltg3exwa_widget_area_array();
					if ( is_front_page() ) {
						if ( is_active_sidebar( $widget_area_array[$hookpoint ]['slug'] ) ) {
							dynamic_sidebar( $widget_area_array[$hookpoint]['slug'] );
						}
					}
				},
				$widget_area_single['priority'],
				1
			);
		}
	}
}
add_action( 'after_setup_theme', 'ltg3exwa_add_widget_area' );

function ltg3exwa_widget_area_array() {
	$widget_area_array = array(
		'lightning_main_section_prepend' => array(
			'slug'     => 'home-content-top-widget-area',
			'label'    => __( 'Home content top', LTG3_EXPAND_WIDGET_AREAS_TEXTDOMAIN ),
			'priority' => 1,
		),
		'lightning_main_section_append'  => array(
			'slug'     => 'home-content-bottom-widget-area',
			'label'    => __( 'Home content bottom', LTG3_EXPAND_WIDGET_AREAS_TEXTDOMAIN ),
			'priority' => 20,
		),
	);
	return apply_filters( 'ltg3exwa_widget_area_array', $widget_area_array );
}

function ltg3exwa_register_widget_area() {
	$widget_area_array = ltg3exwa_widget_area_array();
	foreach ( $widget_area_array as $hookpoint => $widget_area_single ) {
		register_sidebar(
			array(
				'name'          => $widget_area_single['label'],
				'id'            => $widget_area_single['slug'],
				'before_widget' => '<div class="widget %2$s" id="%1$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2>',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'ltg3exwa_register_widget_area' );
