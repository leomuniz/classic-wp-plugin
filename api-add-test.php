<?php
// Insert data.
$data_to_insert = array(
	'name'    => 'John Doe',
	'email'   => 'john@example.com',
	'rating'  => 4,
	'comment' => 'This is a test comment',
);

$insert_url     = 'http://localhost:8888/wp-json/review/v1/add/';
$insert_options = array(
	'http' => array(
		'method'  => 'POST',
		'header'  => 'Content-type: application/json',
		'content' => json_encode( $data_to_insert ),
	),
);

$insert_context = stream_context_create( $insert_options );
$insert_result  = file_get_contents( $insert_url, false, $insert_context );

if ( false === $insert_result ) {
	echo 'Error inserting data';
} else {
	echo 'Data inserted successfully: ' . $insert_result;
}
