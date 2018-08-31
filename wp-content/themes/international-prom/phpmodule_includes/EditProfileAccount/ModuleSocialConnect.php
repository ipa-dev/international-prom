<?php
require_once dirname( __FILE__ ) . '/../../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
( new \Dotenv\Dotenv( __DIR__ . '/../../' ) )->load();
?>
<div class="section group">
	<?php require_once 'module_social_connect/module-facebook.php'; ?>
</div>
<div class="section group">
	<div class="col span_3_of_12">
		<?php require_once 'module_social_connect/module-mailchimp.php'; ?>
	</div>
	<div class="col span_3_of_12">
		<?php require_once 'module_social_connect/module-twitter.php'; ?>
	</div>
	<div class="col span_3_of_12">
		<?php $pinterest_access_token = get_user_meta( $user_ID, 'pinterest_access_token', true ); if ( ! empty( $pinterest_access_token ) ) { ?>
		<span>Connected to Pinterest</span>
		<a class="social_connect" href="<?php bloginfo( 'url' ); ?>/social-disconnect/?social=pinterest">Disconnect</a>
		<?php } else { ?>
		<a class="social_connect" href="https://api.pinterest.com/oauth/?response_type=code&client_id=<?php echo getenv( 'PINTEREST_APP_ID' ); ?>&scope=read_public,write_public&redirect_uri=<?php echo urlencode( get_option( 'PINTEREST_REDIRECT_URI' ) ); ?>">Connect to Pinterest</a>
		<?php } ?>
	</div>
<div class="col span_3_of_12">
	<?php $instagram_access_token = get_user_meta( $user_ID, 'instagram_access_token', true ); if ( ! empty( $instagram_access_token ) ) { ?>
	<span>Connected to Instagram</span>
	<a class="social_connect" href="<?php bloginfo( 'url' ); ?>/social-disconnect/?social=instagram">Disconnect</a>
	<?php } else { ?>
	<a class="social_connect" href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo getenv( 'INSTAGRAM_CLIENT_ID' ); ?>&redirect_uri=<?php echo get_option( 'INSTAGRAM_REDIRECT_URI' ); ?>&response_type=code">Connect to Instagram</a>
	<?php } ?>
</div>
</div>
