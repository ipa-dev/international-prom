<?php
	require_once dirname( __FILE__ ) . '/../../../vendor/autoload.php';
	( new \Dotenv\Dotenv( __DIR__ . '/../../../' ) )->load();
	$pinterest_access_token = get_user_meta( $user_ID, 'pinterest_access_token', true );
if ( ! empty( $pinterest_access_token ) ) {
	?>
	<span>Connected to Pinterest</span>
	<a class="social_connect" href="<?php bloginfo( 'url' ); ?>/social-disconnect/?social=pinterest">Disconnect</a>
<?php } else { ?>
	<a class="social_connect" href="https://api.pinterest.com/oauth/?response_type=code&client_id=<?php echo esc_html( getenv( 'PINTEREST_APP_ID' ) ); ?>&scope=read_public,write_public&redirect_uri=<?php echo rawurlencode( get_option( 'PINTEREST_REDIRECT_URI' ) ); ?>">Connect to Pinterest</a>
<?php } ?>
