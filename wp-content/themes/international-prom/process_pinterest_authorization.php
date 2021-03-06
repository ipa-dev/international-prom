<?php /* Template Name: Pinterest Authorization */ ?>
<?php
require_once __DIR__ . '/vendor/autoload.php';
( new \Dotenv\Dotenv( __DIR__ . '/' ) )->load();
global $user_ID;
$ch = curl_init( 'https://api.pinterest.com/v1/oauth/token' );
curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
curl_setopt(
	$ch, CURLOPT_POSTFIELDS, array(
		'grant_type'    => 'authorization_code',
		'client_id'     => getenv( 'PINTEREST_APP_ID' ),
		'client_secret' => getenv( 'PINTEREST_APP_SECRET' ),
		'code'          => $_GET['code'],
	)
);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

$result     = curl_exec( $ch );
$result_arr = json_decode( $result, true );

$pinterest_access_token = get_user_meta( $user_ID, 'pinterest_access_token', true );
if ( empty( $pinterest_access_token ) ) {
	add_user_meta( $user_ID, 'pinterest_access_token', $result_arr['access_token'] );
} else {
	update_user_meta( $user_ID, 'pinterest_access_token', $result_arr['access_token'] );
}

header( 'Location: ' . get_bloginfo( 'url' ) . '/edit-profile/' );

