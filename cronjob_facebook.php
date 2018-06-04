<?php /* Template Name: Cronjob Facebook */ ?>
<?php
$current_date = date('Y-m-d');
$post_args = array(
	'post_type' => 'cal_post_event',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'meta_query' => array(
		array(
			'key' => 'event_date',
			'value' => $current_date,
			'compare' => '='
		)
	)
);

$cal_post_event_data = new WP_Query($post_args);

if($cal_post_event_data->have_posts()){
	while($cal_post_event_data->have_posts()) : $cal_post_event_data->the_post();
		$cal_post_event_id = get_the_ID();
		echo $post_author_id = get_post_field( 'post_author', $cal_post_event_id );;
		$type = get_post_meta($cal_post_event_id, 'type', true);
		switch ($type) {
			case "facebook":
				$facebook_access_token = get_user_meta($post_author_id, 'facebook_access_token', TRUE);
				if (!empty($facebook_access_token)) {
					require_once ("php-graph-sdk-5.4/src/Facebook/autoload.php");
					$fb = new Facebook\Facebook( [
						'app_id'                => get_option( 'FACEBOOK_APP_ID' ), // Replace {app-id} with your app id
						'app_secret'            => get_option( 'FACEBOOK_APP_SECRET' ),
						'default_graph_version' => 'v2.2',
					] );
					$res = $fb->post( '/me/feed', array(
						'message' => the_excerpt_max_charlength(130)
					), $facebook_access_token );
					$post = $res->getGraphObject();
				}
				break;
			case "twitter":
				$twitter_access_token = get_user_meta($user_ID, 'twitter_access_token', TRUE);
				if (!empty($twitter_access_token)) {
					require_once ("twitteroauth-master/autoload.php");
					$user_id = 4;
					$status = "Test";
					$twitter_access_token = get_user_meta($user_id, 'twitter_access_token', TRUE);
					$twitter_token_secret = get_user_meta($user_id, 'twitter_token_secret', TRUE);
					$connection = new TwitterOAuth(get_option('TWITTER_CONSUMER_KEY'), get_option('TWITTER_CONSUMER_SECRET'), $twitter_access_token, $twitter_token_secret);
					print_r($connection);
					$connection->post('statuses/update', array('status' => "$status"));
					echo $message = "Tweeted Sucessfully!!";
				}
				break;
			case "pinterest":
				$pinterest_access_token = get_user_meta($post_author_id, 'pinterest_access_token', true);
				$ch = curl_init('https://api.pinterest.com/v1/me/search/boards/?query=&access_token='.$pinterest_access_token.'&fields=id');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$result = curl_exec($ch);
				if(!$result) {
					$ch = curl_init('https://api.pinterest.com/v1/pins/?access_token='.$pinterest_access_token.'&fields=id');
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, array(
							'board' => '',
							'note' => get_the_title($post_author_id),
							'image_url' => '',
						)
					);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

					$result1 = curl_exec($ch);
				}
				break;
			case "store_event":
				break;
			case "email":
				break;
			default:
				echo "Your favorite color is neither red, blue, nor green!";
		}
	endwhile;
}
wp_reset_post_data();
