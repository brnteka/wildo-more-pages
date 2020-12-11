<?php
/**
 * Plugin Name: Wildo More Pages
 */

// The widget class
class Wildo_more_pages extends WP_Widget {

	// Main constructor
	public function __construct() {
		parent::__construct(
			'wildo_more_pages',
			__( 'Wildo More Pages'),
			array(
				//'customize_selective_refresh' => true,
			)
		);
		if ( is_active_widget(false, false, $this->id_base) ) {
			wp_enqueue_style( 'wildo-more-pages', plugin_dir_url( __FILE__ ) . 'wildo-more-pages.css', array() );

			if (!wp_script_is( 'alpinejs', 'enqueued' )) {
				wp_enqueue_script('alpinejs', 'https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js', array(), 'latest', true);
			}

			wp_enqueue_script('wildo-more-pages', plugin_dir_url( __FILE__ ) . 'wildo-more-pages.js', array('alpinejs'), false, true);
		}
	}

	// The widget form (for the backend )
	public function form( $instance ) {	
		$defaults = array(
			'number_of_pages_to_show' => '-1',
		);

		// Parse current settings with defaults
	extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

<!-- telegram text -->
<p>
	<label
		for="<?php echo esc_attr( $this->get_field_id( 'number_of_pages_to_show' ) ); ?>"><?php _e( 'Number of pages to show (set \'-1\' to show all)' ); ?></label>
	<input
		class="widefat"
		id="<?php echo esc_attr( $this->get_field_id( 'number_of_pages_to_show' ) ); ?>"
		name="<?php echo esc_attr( $this->get_field_name( 'number_of_pages_to_show' ) ); ?>"
		type="text"
		value="<?php echo esc_attr( $number_of_pages_to_show ); ?>"
	/>
</p>

<?php
	}

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['number_of_pages_to_show']    = isset( $new_instance['number_of_pages_to_show'] ) ? wp_strip_all_tags( $new_instance['number_of_pages_to_show'] ) : '-1';

		return $instance;
	}

	// Display the widget
	public function widget( $args, $instance ) {

		extract( $args );

		// Check the widget options
		$number_of_pages_to_show = isset( $instance['number_of_pages_to_show'] ) ? $instance['number_of_pages_to_show'] : '-1';
	
		// WordPress core before_widget hook (always include )
		echo $before_widget;
	
		// Display the widget
		echo '<div x-data="wildoMorePages()" x-init="setScrollHeight();" class="wildo-more-pages" x-cloak>';

		$pages_query = new WP_Query(
			array(
				'post_type' => 'page',
				'posts_per_page' => $number_of_pages_to_show,
				'order' => 'DESC',
				'orderby' => 'date'
			)
		);

		if ( $pages_query->have_posts() ) {
			?>
<ul
	x-bind:style="'height:' + height + 'px'"
	x-ref="list"
>
	<?php
			while ( $pages_query->have_posts() ) {
				$pages_query->the_post();
				?>
	<li class=""><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></li>
	<?php
			}
			?>
</ul>
<button
	x-text="open ? 'Свернуть' : 'Больше статей'"
	x-on:click="changeHeight"
></button>
<?php
		}

		wp_reset_postdata();

		echo '</div>';
	
		// WordPress core after_widget hook (always include )
		echo $after_widget;
	}
}

// Register the widget
function my_register_wildo_more_pages_widget() {
	register_widget( 'Wildo_more_pages' );
}
add_action( 'widgets_init', 'my_register_wildo_more_pages_widget' );