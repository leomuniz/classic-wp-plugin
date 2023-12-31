<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase, WordPress.NamingConventions.PrefixAllGlobals
/**
 * Shortcodes callback fuctions.
 *
 * @package Classic_WP_Plugin
 */

namespace ClassicWPPlugin\Frontend;

use ClassicWPPlugin\Core\Plugin;
use ClassicWPPlugin\Core\Reviews;

/**
 * Class Shortcodes
 * Shortcodes methods
 *
 * @since 1.0.0
 */
class Shortcodes {

	/**
	 * Shortcode [classic_wp_display_form] to display the form to input data.
	 * It also processes incoming $_POST when submitting the form.
	 *
	 * @since 1.0.0
	 */
	public static function display_form_shortcode() {

		$reviews  = new Reviews();
		$inserted = $reviews->maybe_process_form_submission();

		ob_start();

		if ( ! empty( $inserted ) ) {
			?>
			<p><?php esc_html_e( 'Thank you for submitting your review!', 'classic-wp-plugin' ); ?></p>
			<?php
		} else {
			Plugin::load_view( 'input-form', array( 'inserted' => $inserted ) );
		}

		return ob_get_clean();
	}

	/**
	 * Shortcode [classic_wp_display_list] to display a list with the data in the custom table.
	 * It also process incoming $_POST when searching for an entry.
	 *
	 * @since 1.0.0
	 */
	public static function display_reviews_list_shortcode() {

		$reviews_obj = new Reviews();
		$reviews     = $reviews_obj->get_reviews();

		/**
		 * Filters reviews after fetching them from the database and before the output to the browser.
		 *
		 * @since 1.0.0
		 *
		 * @param array $reviews Found reviews.
		 */
		$reviews = apply_filters( 'classic_wp_plugin_found_reviews', $reviews );

		ob_start();

		/**
		 * Executes before displaying the table output in the shortcode [classic_wp_display_list].
		 *
		 * @since 1.0.0
		 */
		do_action( 'classic_wp_plugin_before_display_reviews_table' );

		if ( empty( $reviews['data'] ) && empty( $reviews['search_query'] ) ) {
			?>
			<h4><?php esc_html_e( 'There are no reviews yet!', 'classic-wp-plugin' ); ?></h4>
			<?php
		} else {

			Plugin::load_view(
				'reviews-table',
				array(
					'reviews'       => $reviews['data'],
					'reviews_count' => $reviews['count'],
				)
			);

			/**
			 * Executes between displaying the table output and the search form in the shotrcode [classic_wp_display_list].
			 *
			 * @since 1.0.0
			 */
			do_action( 'classic_wp_plugin_between_reviews_table_and_search_form' );

			Plugin::load_view( 'search-form', array( 'search_query' => $reviews['search_query'] ) );
		}

		/**
		 * Executes after displaying the search form in the shotrcode [classic_wp_display_list].
		 *
		 * @since 1.0.0
		 */
		do_action( 'classic_wp_plugin_after_display_reviews_table' );

		return ob_get_clean();
	}
}
