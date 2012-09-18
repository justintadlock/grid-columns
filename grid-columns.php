<?php
/*
 * Plugin Name: Grid Columns
 * Plugin URI: http://justintadlock.com
 * Description: A [column] shortcode plugin.
 * Version: 0.1 Alpha
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * @package   GridColumns
 * @version   0.1.0 - Alpha
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2012, Justin Tadlock
 * @link      http://justintadlock.com
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @todo      Lots of testing with the CSS to make sure it works.
 * @todo      Possibly do some error handling for people who don't know rudimentary math.
 */

class Grid_Columns {

	/**
	 * The current grid.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    int
	 */
	public $grid = 0;

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
	public $is_first_column = false;

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
		add_action( 'init', array( &$this, 'register_shortcode' ) );

		/* Enqueue stylesheets on 'wp_enqueue_scripts'. */
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_styles' ), 1 );
	}

	/**
	 * Registers the [column] shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( 'column', array( &$this, 'do_shortcode' ) );
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
			'columns',
			trailingslashit( plugin_dir_url( __FILE__ ) ) . "css/columns$suffix.css",
			null,
			'20120917'
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
		$defaults = apply_filters( 'gc_column_defaults', array( 'grid' => 4, 'span' => 1, 'push' => 0 ) );

		/* Parse the arguments. */
		$attr = shortcode_atts( $defaults, $attr );

		/* Allow devs to filter the arguments. */
		$attr = apply_filters( 'gc_column_args', $attr );

		/* Allow devs to overwrite the allowed grids. */
		$this->allowed_grids = apply_filters( 'gc_allowed_grids', $this->allowed_grids );

		/* Make sure the grid is in the allowed grids array. */
		$attr['grid'] = in_array( $attr['grid'], $this->allowed_grids ) ? absint( $attr['grid'] ) : 4;

		/* Span cannot be greater than the grid. */
		$attr['span'] = ( $attr['grid'] >= $attr['span'] ) ? absint( $attr['span'] ) : $attr['grid'];

		/* The push argument should always be less than the grid. */
		$attr['push'] = ( $attr['grid'] > $attr['push'] ) ? absint( $attr['push'] ) : 0;

		/* Add to the total $span. */
		$this->span = $this->span + $attr['span'] + $attr['push'];

		/* Column classes. */
		$column_classes[] = 'column';
		$column_classes[] = "column-span-{$attr['span']}";
		$column_classes[] = "column-push-{$attr['push']}";

		/* If the $grid property is equal to 0. */
		if ( 0 == $this->grid ) {

			/* Set the grid property to the current grid. */
			/* Note that subsequent shortcodes can't overwrite this until a new set of columns is created. */
			$this->grid = $attr['grid'];

			/* Add the 'column-first' class. */
			$column_classes[] = 'column-first';

			/* Set the $is_first_column property to true. */
			$this->is_first_column = true;
		}

		/* If the $span property is greater than (shouldn't be) or equal to the $grid property. */
		if ( $this->span >= $this->grid ) {

			/* Add the 'column-last' class. */
			$column_classes[] = 'column-last';

			/* Set the $is_last_column property to true. */
			$this->is_last_column = true;
		}

		/* Row classes. */
		$row_classes = array( 'column-grid', "column-grid-{$this->grid}" );

		/* Object properties. */
		$object_vars = get_object_vars( $this );

		/* Allow devs to create custom classes. */
		$row_classes    = apply_filters( 'gc_row_class',    $row_classes,    $attr, $object_vars );
		$column_classes = apply_filters( 'gc_column_class', $column_classes, $attr, $object_vars );

		/* Sanitize and join all classes. */
		$row_class    = join( ' ', array_map( 'sanitize_html_class', array_unique( $row_classes ) ) );
		$column_class = join( ' ', array_map( 'sanitize_html_class', array_unique( $column_classes ) ) );

		/* Output */

		/* If this is the first column. */
		if ( $this->is_first_column ) {

			/* Open a wrapper <div> to contain the columns. */
			$output .= '<div class="' . $row_class . '">';

			/* Set the $is_first_column property back to false. */
			$this->is_first_column = false;
		}

		/* Add the current column to the output. */
		$output .= '<div class="' . $column_class . '">' . wpautop( do_shortcode( $content ) ) . '</div>';

		/* If this is the last column. */
		if ( $this->is_last_column ) {

			/* Close the wrapper. */
			$output .= '</div>';

			/* Set the $is_last_column property back to false. */
			$this->is_last_column = false;

			/* Set the $grid and $span properties back to 0. */
			$this->grid = $this->span = 0;
		}

		/* Return the output of the column. */
		return apply_filters( 'gc_column', $output );
	}
}

new Grid_Columns();

?>