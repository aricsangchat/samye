<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function my_block_cgb_block_assets() { // phpcs:ignore
	// Styles.
	wp_enqueue_style(
		'my_block-cgb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-editor' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);
}

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'my_block_cgb_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function my_block_cgb_editor_assets() { // phpcs:ignore
	// Scripts.
	wp_enqueue_script(
		'my_block-cgb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: File modification time.
		true // Enqueue the script in the footer.
	);

	// Styles.
	wp_enqueue_style(
		'my_block-cgb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);
}

function cgb_grd_home_block_posts( $attributes ) {

	$recent_posts = wp_get_recent_posts(
		array(
			'numberposts' => 3,
			'post_stats'  => 'publish',
			'post_type' => 'grd-teaching',
		)
	);

	$dynamic_block_title =  isset( $attributes['content']) ? sprintf( '<h2>%1$s</h2>', $attributes['content'] ) : '';
	$dynamic_block_description =  isset( $attributes['description']) ? sprintf( '<p>%1$s</p>', $attributes['description'] ) : '';
	$list_item_markup = '';

	foreach ( $recent_posts as $post ) {
		$post_id = $post['ID'];

		$title = get_the_title( $post_id );

		$list_item_markup .= sprintf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( get_permalink( $post_id ) ),
			esc_html( $title )
		);
	}

	$class = 'cgb-grd-home-block';
	if ( isset( $attributes['className'] ) ) {
		$class .= ' ' . $attributes['className'];
	}

	$block_content = sprintf(
		'<div class="%1$s">%2$s%3$s<ul>%4$s</ul></div>',
		esc_attr( $class ),
		$dynamic_block_title,
		$dynamic_block_description,
		$list_item_markup
	);

	return $block_content;
}

function register_dynamic_blocks() {
	register_block_type(
		'cgb/grd-home-block',
		array(
			'attributes' => array(
				'content' => array(
					'type' => 'string',
				),
				'description' => array(
					'type' => 'string',
				),
				'className' => array(
					'type' => 'string',
				),
			),
			'render_callback' => 'cgb_grd_home_block_posts',
		)
	);
}

add_action( 'init', 'register_dynamic_blocks' );


// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'my_block_cgb_editor_assets' );
