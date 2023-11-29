<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase, WordPress.NamingConventions.PrefixAllGlobals
/**
 * Plugin main file. Add hooks, enqueue scripts and set basic features.
 *
 * @package Classic_WP_Plugin
 */

namespace ClassicWPPlugin\Core;

use ClassicWPPlugin;

/**
 * Class Plugin.
 * Basic stuff for the plugin.
 *
 * @since 1.0.0
 */
class Plugin {

	/**
	 * Holds the plugin instance
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private static $instance;

	/**
	 * Singleton get_instance to avoid multiple instances
	 *
	 * @since 1.0.0
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private __construct class
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->init();
	}

	/**
	 * Initialize enqueues, hooks (actions and filters), shortcodes and WP CLI commands.
	 *
	 * @since 1.0.0
	 */
	private function init() {

		// Enqueue Styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

		// Shortcode declaration.
		add_shortcode( 'classic_wp_display_form', array( 'ClassicWPPlugin\Frontend\Shortcodes', 'display_form_shortcode' ) );
		add_shortcode( 'classic_wp_display_list', array( 'ClassicWPPlugin\Frontend\Shortcodes', 'display_reviews_list_shortcode' ) );

		// REST API hooks.
		add_action( 'rest_api_init', array( 'ClassicWPPlugin\Core\RestAPI', 'register_routes' ) );

		Reviews::maybe_create_table();
	}

	/**
	 * Enqueue scripts and styles for frontend and creates ajax_url URL
	 *
	 * @since 1.0.0
	 */
	public function wp_enqueue_scripts() {

		global $post;

		if ( is_singular() && has_shortcode( $post->post_content, 'classic_wp_display_form' ) ) {
			wp_enqueue_style( 'classic-wp-plugin-form-style', ClassicWPPlugin\CSS_URL . 'form-style.css', array(), ClassicWPPlugin\VERSION, 'all' );
		}

		if ( is_singular() && has_shortcode( $post->post_content, 'classic_wp_display_list' ) ) {
			wp_enqueue_style( 'classic-wp-plugin-form-style', ClassicWPPlugin\CSS_URL . 'form-style.css', array(), ClassicWPPlugin\VERSION, 'all' );
			wp_enqueue_style( 'classic-wp-plugin-table-style', ClassicWPPlugin\CSS_URL . 'table-style.css', array(), ClassicWPPlugin\VERSION, 'all' );
		}
	}

	/**
	 * Load HTML view.
	 *
	 * @since 1.0.0
	 *
	 * @param string $view View file name to be loaded.
	 * @param array  $vars Array containing variables to be used in template.
	 */
	public static function load_view( $view, $vars = array() ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter

		$view = ( strpos( $view, '.php' ) !== false ) ? $view : $view . '.php';
		require ClassicWPPlugin\VIEWS_DIR . $view;
	}
}
