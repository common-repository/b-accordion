<?php
/**
 * Plugin Name: B Accordion
 * Description: Display customizable accordion in beautiful way.
 * Version: 1.0.3
 * Author: bPlugins
 * Author URI: https://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: accordion
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'BAB_PLUGIN_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.0.3' );
define( 'BAB_ASSETS_DIR', plugin_dir_url( __FILE__ ) . 'assets/' );

// B Accordion
class BABAccordion{
	function __construct(){
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'init', [$this, 'onInit'] );
	}

	function enqueueBlockAssets(){
		wp_enqueue_script( 'zebra_accordion', BAB_ASSETS_DIR . 'js/zebra_accordion.min.js', [ 'jquery' ], BAB_PLUGIN_VERSION, true );
	}

	function onInit() {
		wp_register_style( 'bab-accordion-editor-style', plugins_url( 'dist/editor.css', __FILE__ ), [ 'bab-accordion-style' ], BAB_PLUGIN_VERSION ); // Backend Style
		wp_register_style( 'bab-accordion-style', plugins_url( 'dist/style.css', __FILE__ ), [], BAB_PLUGIN_VERSION ); // Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'bab-accordion-editor-style',
			'style'				=> 'bab-accordion-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'bab-accordion-editor-script', 'accordion', plugin_dir_path( __FILE__ ) . 'languages' ); // Translate
	}

	function render( $attributes ){
		extract( $attributes );

		$className = $className ?? '';
		$blockClassName = 'wp-block-bab-accordion ' . $className . ' align' . $align;

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='babAccordion-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new BABAccordion;