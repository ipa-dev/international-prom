<?php
	require_once dirname( __FILE__ ) . '/../../../vendor/autoload.php';
	( new \Dotenv\Dotenv( __DIR__ . '/../../../' ) )->load();
	$instagram_access_token = get_user_meta( $user_ID, 'instagram_access_token', true );
if ( ! empty( $instagram_access_token ) ) {
	?>
	<span>Connected to Instagram</span>
	<a class="social_connect" href="<?php bloginfo( 'url' ); ?>/social-disconnect/?social=instagram">Disconnect</a>
<?php } else { ?>
	<a class="social_connect" href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo esc_html( getenv( 'INSTAGRAM_CLIENT_ID' ) ); ?>&redirect_uri=<?php echo rawurlencode( get_option( 'INSTAGRAM_REDIRECT_URI' ) ); ?>&response_type=code">Connect to Instagram</a>
<?php } ?>
