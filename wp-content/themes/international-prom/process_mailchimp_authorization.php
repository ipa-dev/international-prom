<?php php;/* Template Name: Mailchimp Authorization */ ?>
<?php
	require_once __DIR__ . '/vendor/autoload.php';
	( new \Dotenv\Dotenv( __DIR__ . '/' ) )->load();
	global $user_ID;
if ( isset( $_GET['code'] ) ) {
	$url    = 'https://login.mailchimp.com/oauth2/token';
	$fields = array(
		'code'          => $_GET['code'],
		'grant_type'    => 'authorization_code',
		'client_id'     => getenv( 'MAILCHIMP_CLIENT_ID' ),
		'client_secret' => getenv( 'MAILCHIMP_CLIENT_SECRET' ),
		'redirect_uri'  => rawurlencode( getenv( 'MAILCHIMP_REDIRECT_URI' ) ),
	);

	//url-ify the data for the POST
	foreach ( $fields as $key => $value ) {
		$fields_string .= $key . '=' . $value . '&'; }
	$fields_string = rtrim( $fields_string, '&' );

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_POST, count( $fields ) );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

	//execute post
	$result = curl_exec( $ch );
	//close connection
	curl_close( $ch );



	$result_assoc = json_decode( $result, true );
	print_r($result_assoc); exit();

	$access_token = get_user_meta( $user_ID, 'mailchimp_access_token', true );
	if ( empty( $access_token ) ) {
		add_user_meta( $user_ID, 'mailchimp_access_token', $result_assoc['access_token'] );
	} else {
		update_user_meta( $user_ID, 'mailchimp_access_token', $result_assoc['access_token'] );
	}

	$url      = 'https://login.mailchimp.com/oauth2/metadata';
	$header   = array();
	$header[] = 'User-Agent: oauth2-draft-v10';
	$header[] = 'Host: login.mailchimp.com';
	$header[] = 'Accept: application/json';
	$header[] = 'Authorization: OAuth ' . $result_assoc['access_token'];

	$ch = curl_init();

	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

	$result       = curl_exec( $ch );
	$result_assoc = json_decode( $result, true );

	curl_close( $ch );

	$api_endpoint = get_user_meta( $user_ID, 'mailchimp_endpoint', true );
	if ( empty( $api_endpoint ) ) {
		add_user_meta( $user_ID, 'mailchimp_endpoint', $result_assoc['api_endpoint'] );
	} else {
		update_user_meta( $user_ID, 'mailchimp_endpoint', $result_assoc['api_endpoint'] );
	}
	header( 'Location: ' . get_bloginfo( 'url' ) . '/edit-profile/' );
}


