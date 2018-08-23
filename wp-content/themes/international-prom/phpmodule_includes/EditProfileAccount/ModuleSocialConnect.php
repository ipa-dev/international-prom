<div class="section group">
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
      if ( $error != 1 && ! empty( $facebook_access_token ) ) {
        ?>
      <span>Connected to Facebook</span>
      <a class="social_connect" href="<?php bloginfo( 'url' ); ?>/social-disconnect/?social=facebook">Disconnect</a>
        <?php
      } else {

        $helper = $fb->getRedirectLoginHelper();

        $permissions = [ 'email', 'public_profile', 'publish_actions', 'manage_pages', 'publish_pages' ]; // Optional permissions
        $loginUrl    = $helper->getLoginUrl( get_bloginfo( 'url' ) . '/facebook-authorization/', $permissions );

        ?>
      <a class="social_connect" href="<?php echo htmlspecialchars( $loginUrl ); ?>">Connect to Facebook</a>
      <?php } ?>
  </div>
  <div class="col span_8_of_12">
    <?php
    if ( $error != 1 && ! empty( $facebook_access_token ) ) {
      try {
        // Returns a `Facebook\FacebookResponse` object
        $response = $fb->get(
          '/me',
          $facebook_access_token
        );
      } catch ( Facebook\Exceptions\FacebookResponseException $e ) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
      } catch ( Facebook\Exceptions\FacebookSDKException $e ) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
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
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
      } catch ( Facebook\Exceptions\FacebookSDKException $e ) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
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
          if ( $active_page == $page_name['id'] ) {
            echo ' selected'; }
          ?>
           value="<?php echo $page_name['id']; ?>"><?php echo $page_name['name']; ?></option>
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
</div>
<div class="section group">
<div class="col span_3_of_12">
    <?php $mailchimp_access_token = get_user_meta( $user_ID, 'mailchimp_access_token', true ); if ( ! empty( $mailchimp_access_token ) ) { ?>

    <span>Connected to MailChimp</span>
    <a class="social_connect" href="<?php bloginfo( 'url' ); ?>/social-disconnect/?social=mailchimp">Disconnect</a>
  <?php } else { ?>

    <a class="social_connect" href="https://login.mailchimp.com/oauth2/authorize?response_type=code&client_id=<?php echo getenv( 'MAILCHIMP_CLIENT_ID' ); ?>&redirect_uri=<?php echo urlencode( get_option( 'MAILCHIMP_REDIRECT_URI' ) ); ?>">Connect to MailChimp</a>

  <?php } ?>
</div>
<div class="col span_3_of_12">
    <?php $twitter_access_token = get_user_meta( $user_ID, 'twitter_access_token', true ); if ( ! empty( $twitter_access_token ) ) { ?>
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
    <a class="social_connect" href="<?php echo $url; ?>">Connect to Twitter</a>
<?php } ?>
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
