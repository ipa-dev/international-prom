<?php
	require_once dirname( __FILE__ ) . '/../../../vendor/autoload.php';
	use Abraham\TwitterOAuth\TwitterOAuth;
	( new \Dotenv\Dotenv( __DIR__ . '/../../../' ) )->load();
	$twitter_access_token = get_user_meta( $user_ID, 'twitter_access_token', true );
if ( ! empty( $twitter_access_token ) ) {
	?>
		<span>Connected to Twitter</span>
		<a class="social_connect" href="<?php bloginfo( 'url' ); ?>/social-disconnect/?social=twitter">Disconnect</a>
	<?php
} else {
	$connection                     = new TwitterOAuth( getenv( 'TWITTER_CONSUMER_KEY' ), getenv( 'TWITTER_CONSUMER_SECRET' ) );
	$request_token                  = $connection->oauth( 'oauth/request_token', array( 'oauth_callback' => get_option( 'TWITTER_CALLBACK_URL' ) ) );
	$_SESSION['oauth_token']        = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	$url                            = $connection->url( 'oauth/authorize', array( 'oauth_token' => $request_token['oauth_token'] ) );
	?>
	<a class="social_connect" href="<?php echo esc_url( $url ); ?>">Connect to Twitter</a>
<?php } ?>
