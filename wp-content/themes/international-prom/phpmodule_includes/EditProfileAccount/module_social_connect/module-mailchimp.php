<?php
require_once dirname( __FILE__ ) . '/../../../vendor/autoload.php';
( new \Dotenv\Dotenv( __DIR__ . '/../../../' ) )->load();

$mailchimp_access_token = get_user_meta( $user_ID, 'mailchimp_access_token', true );

if ( ! empty( $mailchimp_access_token ) ) { ?>
	<span>Connected to MailChimp</span>
	<a class="social_connect" href="<?php bloginfo( 'url' ); ?>/social-disconnect/?social=mailchimp">Disconnect</a>
<?php } else { ?>
	<a class="social_connect" href="https://login.mailchimp.com/oauth2/authorize?response_type=code&client_id=<?php echo getenv( 'MAILCHIMP_CLIENT_ID' ); ?>&redirect_uri=<?php echo rawurlencode( get_option( 'MAILCHIMP_REDIRECT_URI' ) ); ?>">Connect to MailChimp</a>
<?php } ?>
