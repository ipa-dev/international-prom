<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
( new \Dotenv\Dotenv( __DIR__ . '/../../../' ) )->load();
?>
	<div class="col span_4_of_12">
		<?php
		$fb = new Facebook\Facebook(
			[
				'app_id'                => getenv( 'FACEBOOK_APP_ID' ), // Replace {app-id} with your app id
				'app_secret'            => getenv( 'FACEBOOK_APP_SECRET' ),
				'default_graph_version' => 'v2.2',
			]
		);
		?>
		<?php
		$error                 = 0;
		$facebook_access_token = get_user_meta( $user_ID, 'facebook_access_token', true );
		if ( ! empty( $facebook_access_token ) ) {
			try {
				// Returns a `Facebook\FacebookResponse` object
				$response = $fb->get(
					'/me',
					$facebook_access_token
				);
			} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
				//echo 'Graph returned an error: ' . $e->getMessage();
				//exit;
				$error = 1;
				goto break_free_of_try;
			} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
				//echo 'Facebook SDK returned an error: ' . $e->getMessage();
				//exit;
				$error = 1;
				goto break_free_of_try;
			}
		}
		break_free_of_try:
		if ( 1 !== $error && ! empty( $facebook_access_token ) ) {
			?>
		<span>Connected to Facebook</span>
		<a class="social_connect" href="<?php bloginfo( 'url' ); ?>/social-disconnect/?social=facebook">Disconnect</a>
			<?php
		} else {

			$helper = $fb->getRedirectLoginHelper();

			$permissions = [ 'email', 'public_profile', 'publish_actions', 'manage_pages', 'publish_pages' ]; // Optional permissions
			$login_url   = $helper->getLoginUrl( get_bloginfo( 'url' ) . '/facebook-authorization/', $permissions );

			?>
		<a class="social_connect" href="<?php echo esc_html( $login_url ); ?>">Connect to Facebook</a>
		<?php } ?>
	</div>
	<div class="col span_8_of_12">
	<?php
	if ( 1 !== $error && ! empty( $facebook_access_token ) ) {
		try {
			// Returns a `Facebook\FacebookResponse` object
			$response = $fb->get(
				'/me',
				$facebook_access_token
			);
		} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
			echo 'Graph returned an error: ' . esc_html( $e->getMessage() );
			exit;
		} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
			echo 'Facebook SDK returned an error: ' . esc_html( $e->getMessage() );
			exit;
		}
		$user = $response->getGraphUser();

		try {
			// Returns a `Facebook\FacebookResponse` object
			$response = $fb->get(
				'/' . $user['id'] . '/accounts',
				$facebook_access_token
			);
		} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
			echo 'Graph returned an error: ' . esc_html( $e->getMessage() );
			exit;
		} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
			echo 'Facebook SDK returned an error: ' . esc_html( $e->getMessage() );
			exit;
		}
		$active_page = get_user_meta( $user_ID, 'active_page', true );
		?>
		<span>Choose which page where you would like posts to appear:</span>
		<select name="page_options" form="joinus">
		<?php
		if ( ! empty( $response->getDecodedBody()['data'] ) ) {
			foreach ( $response->getDecodedBody()['data'] as $page_name ) {
				?>
			<option
				<?php
				if ( $active_page === $page_name['id'] ) {
					echo ' selected'; }
				?>
			value="<?php echo esc_attr( $page_name['id'] ); ?>"><?php echo esc_attr( $page_name['name'] ); ?></option>
				<?php
			}
		} else {
			?>
		<option value="me">Personal Page</option>
			<?php
		}
		?>
	</select>
		<?php
	}
	?>
	</div>
