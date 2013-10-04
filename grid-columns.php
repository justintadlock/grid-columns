<?php
/*
 * Plugin Name: Grid Columns
 * Plugin URI: http://themehybrid.com/plugins/grid-columns
 * Description: A [column] shortcode plugin.
 * Version: 0.2.0
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * Grid Columns was created because of the sheer number of WordPress themes adding poorly-coded 
 * column shortcodes, which lock users into use that theme and system forever.  The plugin is 
 * meant to be an all-around solution for any WordPress user, regardless of theme, to be able 
 * to have columnized content using a simple [column] shortcode.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package   GridColumns
 * @version   0.2.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2012 - 2013, Justin Tadlock
 * @link      http://themehybrid.com/plugins/grid-columns
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Grid_Columns {

	/**
	 * Holds the instance of this class.
	 *
	 * @since  0.2.0
	 * @access private
	 * @var    object
	 */
	private static $instance;

	/**
	 * The current grid.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    int
	 */
	public $grid = 4;

	/**
	 * The current total number of columns in the grid.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    int
	 */
	public $span = 0;

	/**
	 * Whether we're viewing the first column.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    bool
	 */
	public $is_first_column = true;

	/**
	 * Whether we're viewing the last column.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    bool
	 */
	public $is_last_column = false;

	/**
	 * Allowed grids can be 2, 3, 4, 5, or 12 columns.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array
	 */
	public $allowed_grids = array( 2, 3, 4, 5, 12 );

	/**
	 * Sets up our actions/filters.
	 *
	 * @since 0.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Register shortcodes on 'init'. */
		add_action( 'init', array( $this, 'register_shortcode' ) );

		/* Enqueue stylesheets on 'wp_enqueue_scripts'. */
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 1 );

		/* Apply filters to the column content. */
		add_filter( 'gc_column_content', 'wpautop' );
		add_filter( 'gc_column_content', 'shortcode_unautop' );
		add_filter( 'gc_column_content', 'do_shortcode' );
	}

	/**
	 * Registers the [column] shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( 'column', array( $this, 'do_shortcode' ) );
	}

	/**
	 * Enqueues the columns.css stylesheet to make the columns pretty.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function enqueue_styles() {

		/* Use the .min stylesheet if SCRIPT_DEBUG is turned off. */
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		/* Enqueue the stylesheet. */
		wp_enqueue_style(
			'grid-columns',
			trailingslashit( plugin_dir_url( __FILE__ ) ) . "css/columns$suffix.css",
			null,
			'20130123'
		);
	}

	/**
	 * Returns the content of the column shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array  $attr The user-inputted arguments.
	 * @param  string $content The content to wrap in a shortcode.
	 * @return string
	 */
	public function do_shortcode( $attr, $content = null ) {

		/* If there's no content, just return back what we got. */
		if ( is_null( $content ) )
			return $content;

		/* Set up the default variables. */
		$output = '';
		$row_classes = array();
		$column_classes = array();

		/* Set up the default arguments. */
		$defaults = apply_filters(
			'gc_column_defaults',
			array(
				'grid'  => $this->grid,
				'span'  => 1,
				'push'  => 0,
				'class' => ''
			)
		);

		/* Parse the arguments. */
		$attr = shortcode_atts( $defaults, $attr );

		/* Allow devs to filter the arguments. */
		$attr = apply_filters( 'gc_column_args', $attr );

		/* Allow devs to overwrite the allowed grids. */
		$this->allowed_grids = apply_filters( 'gc_allowed_grids', $this->allowed_grids );

		/* Make sure the grid is in the allowed grids array. */
		if ( $this->is_first_column && in_array( $attr['grid'], $this->allowed_grids ) )
			$this->grid = absint( $attr['grid'] );

		/* Span cannot be greater than the grid. */
		$attr['span'] = ( $this->grid >= $attr['span'] ) ? absint( $attr['span'] ) : 1;

		/* The push argument should always be less than the grid. */
		$attr['push'] = ( $this->grid > $attr['push'] ) ? absint( $attr['push'] ) : 0;

		/* Add to the total $span. */
		$this->span = $this->span + $attr['span'] + $attr['push'];

		/* Column classes. */
		$column_classes[] = 'column';
		$column_classes[] = "column-span-{$attr['span']}";
		$column_classes[] = "column-push-{$attr['push']}";

		/* Add user-input custom class(es). */
		if ( !empty( $attr['class'] ) ) {
			if ( !is_array( $attr['class'] ) )
				$attr['class'] = preg_split( '#\s+#', $attr['class'] );
			$column_classes = array_merge( $column_classes, $attr['class'] );
		}

		/* Add the 'column-first' class if this is the first column. */
		if ( $this->is_first_column )
			$column_classes[] = 'column-first';

		/* If the $span property is greater than (shouldn't be) or equal to the $grid property. */
		if ( $this->span >= $this->grid ) {

			/* Add the 'column-last' class. */
			$column_classes[] = 'column-last';

			/* Set the $is_last_column property to true. */
			$this->is_last_column = true;
		}

		/* Object properties. */
		$object_vars = get_object_vars( $this );

		/* Allow devs to create custom classes. */
		$column_classes = apply_filters( 'gc_column_class', $column_classes, $attr, $object_vars );

		/* Sanitize and join all classes. */
		$column_class = join( ' ', array_map( 'sanitize_html_class', array_unique( $column_classes ) ) );

		/* Output */

		/* If this is the first column. */
		if ( $this->is_first_column ) {

			/* Row classes. */
			$row_classes = array( 'column-grid', "column-grid-{$this->grid}" );
			$row_classes = apply_filters( 'gc_row_class', $row_classes, $attr, $object_vars );
			$row_class = join( ' ', array_map( 'sanitize_html_class', array_unique( $row_classes ) ) );

			/* Open a wrapper <div> to contain the columns. */
			$output .= '<div class="' . $row_class . '">';

			/* Set the $is_first_column property back to false. */
			$this->is_first_column = false;
		}

		/* Add the current column to the output. */
		$output .= '<div class="' . $column_class . '">' . apply_filters( 'gc_column_content', $content ) . '</div>';

		/* If this is the last column. */
		if ( $this->is_last_column ) {

			/* Close the wrapper. */
			$output .= '</div>';

			/* Reset the properties that have been changed. */
			$this->reset();
		}

		/* Return the output of the column. */
		return apply_filters( 'gc_column', $output );
	}

	/**
	 * Resets the properties to their original states.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function reset() {

		foreach ( get_class_vars( __CLASS__ ) as $name => $default ) {

			if ( 'instance' !== $name )
				$this->$name = $default;
		}
	}

	/**
	 * Returns the instance.
	 *
	 * @since  0.2.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
}

Grid_Columns::get_instance();

?>