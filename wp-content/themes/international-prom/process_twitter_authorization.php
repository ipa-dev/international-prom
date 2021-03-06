<?php /* Template Name: Twitter Authorization */ ?>
<?php
global $user_ID;
if ( ! session_id() ) {
	session_start();
}
	  require_once 'vendor/autoload.php';
	  ( new \Dotenv\Dotenv( __DIR__ . '/' ) )->load();
	  use Abraham\TwitterOAuth\TwitterOAuth;
	  $connection           = new TwitterOAuth( getenv( 'TWITTER_CONSUMER_KEY' ), getenv( 'TWITTER_CONSUMER_SECRET' ), $_SESSION['oauth_token'], $_SESSION['oauth_token_secret'] );
	  $access_token         = $connection->oauth( 'oauth/access_token', [ 'oauth_verifier' => $_GET['oauth_verifier'] ] );
	  $twitter_access_token = get_user_meta( $user_ID, 'twitter_access_token', true );
if ( empty( $twitter_access_token ) ) {
	add_user_meta( $user_ID, 'twitter_access_token', $access_token['oauth_token'] );
	add_user_meta( $user_ID, 'twitter_token_secret', $access_token['oauth_token_secret'] );
} else {
	update_user_meta( $user_ID, 'twitter_access_token', $access_token['oauth_token'] );
	update_user_meta( $user_ID, 'twitter_token_secret', $access_token['oauth_token_secret'] );
}
	  header( 'Location: ' . get_bloginfo( 'url' ) . '/edit-profile/' );

