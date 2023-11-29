<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase, WordPress.NamingConventions.PrefixAllGlobals
/**
 * REST API class. Register custom API endpoints.
 *
 * @package Classic_WP_Plugin
 */

namespace ClassicWPPlugin\Core;

/**
 * Reviews class.
 *
 * @since 1.0.0
 */
class RestAPI {

	/**
	 * Register API routes to add and get data from the custom table.
	 *
	 * @since 1.0.0
	 */
	public static function register_routes() {

		$custom_endpoint = 'review/v1';

		register_rest_route(
			$custom_endpoint,
			'/add/',
			array(
				'methods'             => 'POST',
				'callback'            => array( 'ClassicWPPlugin\Core\RestAPI', 'insert_review' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			$custom_endpoint,
			'/get/',
			array(
				'methods'             => 'GET',
				'callback'            => array( 'ClassicWPPlugin\Core\RestAPI', 'get_reviews' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Process API review/v1/add endpoint. Insert data on the custom table.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Fields to be inserted on the table.
	 */
	public static function insert_review( $data ) {

		// $data will be sanitized on the Review() class.
		$reviews = new Reviews();
		$result  = $reviews->save_data(
			array(
				'name'    => $data['name'],
				'email'   => $data['email'],
				'rating'  => $data['rating'],
				'comment' => $data['comment'],
			)
		);

		if ( $result ) {
			return new \WP_REST_Response( 'Data inserted successfully', 200 );
		} else {
			return new \WP_REST_Response( 'Failed to insert data', 500 );
		}
	}

	/**
	 * Process API review/v1/get endpoint. Retrieve all the fields from the custom table.
	 *
	 * @since 1.0.0
	 *
	 * @param array $request Parameters to filter the query.
	 */
	public static function get_reviews( $request ) {

		// Review class sanitizes the get data.
		$reviews_obj = new Reviews();
		$reviews     = $reviews_obj->get_reviews(
			array(
				'page'     => $request['page'],
				'per_page' => absint( $request['per_page'] ) > 100 ? 100 : $request['per_page'], // Prevent requesting +100 rows per page.
				'orderby'  => 'id',
				'order'    => 'desc',
				'q'        => $request['q'],
			)
		);

		$response = new \WP_REST_Response( $reviews, 200 );

		$response->set_headers( array( 'Content-Type' => 'application/json' ) );

		return $response;
	}
}
