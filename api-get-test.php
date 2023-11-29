<?php
// Fetch data
$get_url    = 'http://localhost:8888/wp-json/review/v1/get/';
$get_result = file_get_contents( $get_url );

if ( false === $get_result ) {
	echo 'Error fetching data';
} else {
	$data = json_decode( $get_result, true );
	print_r( $data );
}
