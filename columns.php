<?php
/*
 * Plugin Name: Columns
 * Plugin URI: http://justintadlock.com
 * Description: A [column] shortcode plugin.
 * Version: 0.1 Alpha
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
*/


add_action( 'init', 'columns_register_shortcodes' );

add_action( 'wp_enqueue_scripts', 'columns_enqueue_styles', 1 );

function columns_enqueue_styles() {

	wp_enqueue_style(
		'columns',
		trailingslashit( plugin_dir_url( __FILE__ ) ) . 'columns.css',
		null,
		'20120913',
		'all'
	);
}

function columns_register_shortcodes() {
	add_shortcode( 'column', 'columns_do_shortcode' );
}

function columns_do_shortcode( $attr, $content = null ) {
	global $_columns_grid, $_columns_span;

	if ( is_null( $content ) )
		return $content;

	$defaults = array(
		'grid' => 10,
		'span' => '', // will be overwritten to be the same as $grid if empty
		'class' => '',
	);

	$attr = shortcode_atts( $defaults, $attr );

	$output = '';
	$classes = array();

	$classes[] = 'column';
	$classes[] = "column-span-{$attr['span']}";

	if ( isset( $attr['class'] ) )
		$classes[] = $attr['class'];

	$is_first_column = false;
	$is_last_column = false;
	$allowed_grid = array( 10, 12, 16 );
	$allowed_span = array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 );
	$attr['grid'] = absint( $attr['grid'] );
	$attr['span'] = absint( $attr['span'] );

	if ( 16 == $attr['grid'] )
		$allowed_span = array_merge( $allowed_span, array( 13, 14, 15, 16 ) );


	if ( empty( $_columns_grid ) ) {
		$_columns_grid = $grid = in_array( $attr['grid'], $allowed_grid ) ? $attr['grid'] : 12;
		$classes[] = 'column-first';
		$is_first_column = true;
	}

	if ( empty( $attr['span'] ) )
		$attr['span'] = $grid;

	$_columns_span = in_array( $attr['span'], $allowed_span ) ? $_columns_span + $attr['span'] : $_columns_span;

	if ( $_columns_grid == $_columns_span ) {
		$classes[] = 'column-last';
		$is_last_column = true;

		$_columns_grid = 0;
		$_columns_span = 0;
	}

	$class = join( ' ', array_map( 'sanitize_html_class', array_unique( $classes ) ) );

	if ( $is_first_column )
		$output .= '<div class="column-grid ' . sanitize_html_class( "column-grid-{$attr['grid']}" ) . '">';

	$output .= '<div class="' . $class . '">' . wpautop( do_shortcode( $content ) ) . '</div>';

	if ( $is_last_column )
		$output .= '</div>';

	return $output;
}






?>