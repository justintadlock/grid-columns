<?php
/*
 * Plugin Name: Columns
 * Plugin URI: http://justintadlock.com
 * Description: A [column] shortcode plugin.
 * Version: 0.1 Alpha
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * @package   Columns
 * @version   0.1.0 - Alpha
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2012, Justin Tadlock
 * @link      http://justintadlock.com
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @todo      Lots of testing with the CSS to make sure it works.
 * @todo      Better name???
 * @todo      Possibly do some error handling for people who don't know rudimentary math.
 */

class Columns {

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
	 * Allowed grids can be 10, 12, or 16 columns.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array
	 */
	public $allowed_grid = array( 10, 12, 16 );

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

		wp_enqueue_style(
			'columns',
			trailingslashit( plugin_dir_url( __FILE__ ) ) . 'columns.css',
			null,
			'20120913'
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
		$classes = array();
		$attr = shortcode_atts( array( 'grid' => 10, 'span' => 1, 'push' => NULL ), $attr );

		/* Only allow grids 10, 12, 16. */
		$attr['grid'] = in_array( $attr['grid'], $this->allowed_grid ) ? absint( $attr['grid'] ) : 10;

		/* Span cannot be greater than the grid. */
		$attr['span'] = ( $attr['grid'] >= $attr['span'] ) ? absint( $attr['span'] ) : $attr['grid'];
                
                /* Push cannot be greater than grid - 1 */
                $attr['push'] = ( $attr['grid'] - 1 >= $attr['push'] ) ? absint( $attr['push'] ) : NULL;

		/* Add to the total $span. */
		$this->span = $this->span + $attr['span'] + $attr['push'];

		/* Classes. */
		$classes[] = 'column';
		$classes[] = "column-span-{$attr['span']}";
                if ( $attr['push'] ) $classes[] = "push-{$attr['push']}";

		/* If the $grid property is equal to 0. */
		if ( 0 == $this->grid ) {

			/* Set the grid property to the current grid. */
			/* Note that subsequent shortcodes can't overwrite this until a new set of columns are created. */
			$this->grid = $attr['grid'];

			/* Add the 'column-first' class. */
			$classes[] = 'column-first';

			/* Set the $is_first_column property to true. */
			$this->is_first_column = true;
		}

		/* If the $span property is greater than (shouldn't be) or equal to the $grid property. */
		if ( $this->span >= $this->grid ) {

			/* Add the 'column-last' class. */
			$classes[] = 'column-last';

			/* Set the $is_last_column property to true. */
			$this->is_last_column = true;
		}

		/* Sanitize and join all classes. */
		$class = join( ' ', array_map( 'sanitize_html_class', array_unique( $classes ) ) );

		/* Output */

		/* If this is the first column. */
		if ( $this->is_first_column ) {

			/* Open a wrapper <div> to contain the columns. */
			$output .= '<div class="column-grid ' . sanitize_html_class( "column-grid-{$this->grid}" ) . '">';

			/* Set the $is_first_column property back to false. */
			$this->is_first_column = false;
		}

		/* Add the current column to the output. */
		$output .= '<div class="' . $class . '">' . wpautop( do_shortcode( $content ) ) . '</div>';

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
		return $output;
	}
}

new Columns();

?>