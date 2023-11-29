<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase, WordPress.NamingConventions.PrefixAllGlobals
/**
 * Reviews class. Handle form submission, insert and retrieve data to/from database.
 *
 * @package Classic_WP_Plugin
 */

namespace ClassicWPPlugin\Core;

/**
 * Reviews class.
 *
 * @since 1.0.0
 */
class Reviews {

	/**
	 * Reviews table name.
	 *
	 * @since 1.7.5
	 */
	const TABLENAME = 'classic_wp_plugin';

	/**
	 * Create custom Reviews table if not exists.
	 *
	 * @since 1.0.0
	 */
	public static function maybe_create_table() {

		global $wpdb;

		$tablename       = $wpdb->prefix . self::TABLENAME;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS {$tablename} (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			name varchar(200) NOT NULL,
			email varchar(200) NOT NULL,
			rating tinyint (20) NOT NULL,
			comment text,
			date_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			date_modified datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( $sql );
	}

	/**
	 * Check the new review form submission and process the $_POST data.
	 *
	 * @since 1.0.0
	 */
	public function maybe_process_form_submission() {

		if ( ! empty( $_POST ) && isset( $_POST['classic_wp_plugin_name'] ) ) {

			if ( empty( $_POST['_wp_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wp_nonce'] ) ), 'classic_wp_plugin_nonce_' . get_the_ID() ) ) {
				die( esc_html( __( 'Something went wrong.', 'classic-wp-plugin' ) ) );
			}

			return $this->save_data( $_POST );
		}

		return null;
	}

	/**
	 * Public method to save data on the database.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Input data. $_POST or $data from REST API request.
	 */
	public function save_data( $data ) {
		return $this->insert_data( $this->sanitize_input_data( $data ) );
	}

	/**
	 * Sanitize input data before writing to the database.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input_data Input data. $_POST or $data from REST API request.
	 */
	private function sanitize_input_data( $input_data ) {

		$input_data = $this->maybe_remove_prefix( $input_data );

		// Sanitize data. Whitelist to ignore other possible input variables.
		$data = array(
			'name'    => ! empty( $input_data['name'] ) ? sanitize_text_field( wp_unslash( $input_data['name'] ) ) : '',
			'email'   => ! empty( $input_data['email'] ) ? sanitize_email( wp_unslash( $input_data['email'] ) ) : '',
			'rating'  => ! empty( $input_data['rating'] ) ? absint( $input_data['rating'] ) : '',
			'comment' => ! empty( $input_data['comment'] ) ? sanitize_text_field( wp_unslash( $input_data['comment'] ) ) : '',
		);

		$this->post_is_sanitized = true;

		return $data;
	}

	/**
	 * Insert data into the custom table.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Fields to be inserted on the table.
	 */
	private function insert_data( $data ) {

		global $wpdb;

		if ( empty( $this->post_is_sanitized ) || empty( $data['name'] ) || empty( $data['email'] ) || empty( $data['rating'] ) ) {
			return false;
		}

		// Add date fields if not provided.
		if ( ! isset( $data['date_created'] ) ) {
			$data['date_created'] = current_time( 'mysql' );
		}

		if ( ! isset( $data['date_modified'] ) ) {
			$data['date_modified'] = current_time( 'mysql' );
		}

		/**
		 * Filters the inserted data before it's saved to the database.
		 *
		 * @since 1.0.0
		 *
		 * @param array $data The data.
		 */
		$data = apply_filters( 'classic_wp_plugin_insert_data', $data );

		/**
		 * Executes before the data is inserted.
		 *
		 * @since 1.0.0
		 *
		 * @param array $data The data.
		 */
		do_action( 'classic_wp_plugin_before_insert_data', $data );

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$result = $wpdb->insert(
			$wpdb->prefix . self::TABLENAME,
			$data,
			array( '%s', '%s', '%d', '%s', '%s', '%s' )
		);

		/**
		 * Executes after the data is inserted.
		 *
		 * @since 1.0.0
		 *
		 * @param array     $data The data.
		 * @param int|false $id   The insert ID, or false on error.
		 */
		do_action( 'classic_wp_plugin_after_insert_data', $data, $wpdb->insert_id );

		return ( false !== $result );
	}

	/**
	 * Get reviews. If $params are empty, fetch the data from $_POST.
	 *
	 * @since 1.0.0
	 *
	 * @param array $params Array of params to filter the request.
	 */
	public function get_reviews( $params = array() ) {

		$params  = empty( $params ) ? $this->fetch_post_search_query() : $params;
		$params  = $this->sanitize_get_data( $this->maybe_remove_prefix( $params ) );
		$reviews = $this->get_table_data( $params );

		return array(
			'search_query' => $params['q'],
			'count'        => count( $reviews ),
			'data'         => $reviews,
		);
	}

	/**
	 * Fetch $_POST search query to filter the get_reviews.
	 * Other fields are currently hard-coded for browser output.
	 *
	 * @since 1.0.0
	 */
	public function fetch_post_search_query() {

		$fields = array(
			'page'     => 0,
			'per_page' => 10,
			'orderby'  => 'id',
			'order'    => 'desc',
		);

		if ( isset( $_POST['classic_wp_plugin_q'] ) ) {

			if ( empty( $_POST['_wp_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wp_nonce'] ) ), 'classic_wp_plugin_search_nonce_' . get_the_ID() ) ) {
				die( esc_html( __( 'Something went wrong.', 'classic-wp-plugin' ) ) );
			}

			$fields = array_merge( $fields, $_POST );
		}

		return $fields;
	}

	/**
	 * Sanitize data before fetching reviews from the database.
	 * Whitelist fields to ignore unneeded fields from $_POST or REST API request data.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields Array of fields to filter the request.
	 */
	private function sanitize_get_data( $fields = array() ) {

		$fields['q']        = isset( $fields['q'] ) ? trim( sanitize_text_field( $fields['q'] ) ) : '';
		$fields['page']     = isset( $fields['page'] ) ? absint( $fields['page'] ) : 0;
		$fields['per_page'] = isset( $fields['per_page'] ) ? absint( $fields['per_page'] ) : 10;
		$fields['order']    = 'desc';
		$fields['orderby']  = 'id';

		$this->get_is_sanitized = true;

		return $fields;
	}

	/**
	 * Shortcode [classic_wp_display_form] to display a list with the data in the custom table.
	 * It also process incoming $_POST when searching for an entry.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields Array of fields to filter the request.
	 */
	private function get_table_data( $fields ) {

		global $wpdb;

		if ( empty( $this->get_is_sanitized ) ) {
			return array();
		}

		$tablename    = $wpdb->prefix . self::TABLENAME;
		$query_params = array();

		$where_clause = '';
		if ( ! empty( $fields['q'] ) ) {
			$where_clause  = ' WHERE name LIKE %s ';
			$where_clause .= ' OR email LIKE %s ';
			$where_clause .= ' OR comment LIKE %s ';

			array_push( $query_params, "%{$fields['q']}%", "%{$fields['q']}%", "%{$fields['q']}%" );
		}

		$query = "SELECT * FROM {$tablename} {$where_clause} ORDER BY {$fields['orderby']} {$fields['order']} LIMIT %d,%d;";

		array_push( $query_params, absint( $fields['page'] ), absint( $fields['per_page'] ) );

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->get_results( $wpdb->prepare( $query, $query_params ) );
	}

	/**
	 * Remove the `classic_wp_plugin` from array keys if exists.
	 * `name` attributes on HTML forms have a plugin prefix that doesn't exist on the REST API calls.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Array to remove the key prefixes.
	 */
	private function maybe_remove_prefix( $data ) {

		$normalized_keys = array_map(
			function ( $key ) {
				return str_replace( 'classic_wp_plugin_', '', $key );
			},
			array_keys( $data )
		);

		return array_combine( $normalized_keys, $data );
	}
}
