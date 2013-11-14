<?php
/*
Plugin Name: Swiffy Widget
Plugin URI: http://dustyf.com
Description: Add Swiffy converted Flash files to WordPress Widget Areas
Author: dustyf
Author URI: http://dustyf.com
Version: 0.1
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

// Creating the widget 
class df_swiffy_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'df_swiffy_widget', 

			// Widget name will appear in UI
			__( 'Swiffy Widget', 'df_swiffy_widget' ), 

			// Widget description
			array( 'description' => __( 'Add a Swiffy converted Flash Animation to your widget area', 'df_swiffy_widget' ), ) 
		);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		// This is where you run the code and display the output
		$p_id = get_the_id();
		$swiffyjs = get_field( 'swiffy_js_file', $p_id );
		$styles = ' style="';
		if ( get_field( 'swiffy_width', $p_id ) ){
			$styles .= 'width:' . get_field( 'swiffy_width', $p_id ) . ';';
		}
		if ( get_field( 'swiffy_max_width', $p_id ) ){
			$styles .= 'max-width:' . get_field( 'swiffy_width', $p_id ) . ';';
		}
		if ( get_field( 'swiffy_min_width', $p_id ) ){
			$styles .= 'min-width:' . get_field( 'swiffy_width', $p_id ) . ';';
		}
		if ( get_field( 'swiffy_height', $p_id ) ){
			$styles .= 'height:' . get_field( 'swiffy_height', $p_id ) . ';';
		}
		if ( get_field( 'swiffy_max_height', $p_id ) ){
			$styles .= 'max-height:' . get_field( 'swiffy_max_height', $p_id ) . ';';
		}
		if ( get_field( 'swiffy_min_height', $p_id ) ){
			$styles .= 'min-height:' . get_field( 'swiffy_min_height', $p_id ) . ';';
		}
		$styles .= '"';
		$fallback = get_field( 'swiffy_fallback_image', $p_id );

		echo '<script src="' . $swiffyjs['url'] . '"></script>';
		echo '<div id="' . get_field( 'swiffy_animation_id', $p_id ) . '"' . $styles . '></div>';
		echo '<script>
			if (Modernizr.svg) {
				var stage = new swiffy.Stage(document.getElementById("' . get_field( 'swiffy_animation_id', $p_id ) . '"), swiffyobject);';
		if ( get_field( 'swiffy_transparent_background', $p_id ) ) {
			echo 'stage.setBackground(null);';
		}
		echo 'stage.start();
			} else {
				document.write(\'<img src="' . $fallback['url'] . '" />\')
			}
    		</script>';
		echo $args['after_widget'];
	}
			
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'New title', 'df_swiffy_widget' );
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		

		<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // Class df_swiffy_widget ends here

// Register and load the widget
function df_swiffy_load_widget() {
	register_widget( 'df_swiffy_widget' );
}
add_action( 'widgets_init', 'df_swiffy_load_widget' );

/**
 * Enqueue the Swiffy Runtime Script only if the widget is active
 */
function df_swiffy_enqueue_scripts() {
	if ( is_active_widget( false, false, 'df_swiffy_widget' ) ) {
		wp_enqueue_script( 'swiffy-runtime', 'https://www.gstatic.com/swiffy/v5.3/runtime.js', array(), '20131113' );
	}
}
add_action( 'wp_enqueue_scripts', 'df_swiffy_enqueue_scripts' );

/**
 * Custom fields
 *
 * This currently requires the use of Advanced Custom Fields. Fields
 * are shown on individual Posts or Pages not in the Widget itself
 * By default, the fields are set to show on all posts and pages. To
 * change this edit the location section of the array below. You can
 * generate and export code in ACF and replace that section too.
 */
if ( function_exists( 'register_field_group' ) ) {
	register_field_group( array (
		'id' => 'acf_swiffy-widget',
		'title' => 'Swiffy Widget',
		'fields' => array (
			array (
				'key' => 'field_5283fd653453e',
				'label' => __('Swiffy JavaScript File'),
				'name' => 'swiffy_js_file',
				'type' => 'file',
				'instructions' => __( 'Take the swiffyobject data within the script tags and add this to it\'s own .js file. Upload this file here.' ),
				'save_format' => 'object',
				'library' => 'all',
			),
			array (
				'key' => 'field_528400b93453f',
				'label' => __('Fallback Image'),
				'name' => 'swiffy_fallback_image',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
			array (
				'key' => 'field_528400de34540',
				'label' => __('Transparent Background?'),
				'name' => 'swiffy_transparent_background',
				'type' => 'true_false',
				'message' => '',
				'default_value' => 0,
			),
			array (
				'key' => 'field_528400fe34541',
				'label' => __('Width'),
				'name' => 'swiffy_width',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5284f5021593f',
				'label' => __('Max Width'),
				'name' => 'swiffy_max_width',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5284f51915940',
				'label' => __('Min Width'),
				'name' => 'swiffy_min_width',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5284011a34542',
				'label' => __('Height'),
				'name' => 'swiffy_height',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5284f52415941',
				'label' => __('Max Height'),
				'name' => 'swiffy_max_height',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5284f52e15942',
				'label' => __('Min Height'),
				'name' => 'swiffy_min_height',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5284013534543',
				'label' => __('Animation ID'),
				'name' => 'swiffy_animation_id',
				'type' => 'text',
				'instructions' => __('Add a unique ID to this animation.	Be sure this is unique to other pages and HTML elements on the page.	No spaces are allowed, please use only numbers, letters, and underscores.'),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 1,
				),
			),
		),
		'options' => array (
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	) );
}

