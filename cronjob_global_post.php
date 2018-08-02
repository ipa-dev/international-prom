<?php /* Template Name: Cronjob Global Post */ ?>
<?php
require_once('vendor/autoload.php');
$tz = get_option('timezone_string');
date_default_timezone_set($tz);
$current_date1 = date('Y-m-d');
echo $current_time1 = date('g:i A');

$fb = new Facebook\Facebook( [
	'app_id'                => get_option( 'FACEBOOK_APP_ID' ), // Replace {app-id} with your app id
	'app_secret'            => get_option( 'FACEBOOK_APP_SECRET' ),
	'default_graph_version' => 'v2.2',
] );
use Abraham\TwitterOAuth\TwitterOAuth;

$user_query = new WP_User_Query( array( 'role' => 'retailer' ) );
$users = $user_query->get_results();

$users_array = array();

foreach($users as $user){
	array_push($users_array , $user->ID);
	//$user_id = $user->ID;
}
foreach($users_array as $user_id){
	//echo $user_id.'<br/>';
	$optOut = json_decode(get_user_meta($user_id, 'optOut', true));
	$args1 = array(
		'p' => 38414,
		'post_type' => 'global_event',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'post__not_in' => $optOut,
		/*'meta_query' => array(
			array(
				'key' => 'event_date',
				'value' => $current_date1,
				'compare' => '='
			),
			'relation' => 'AND',
			array(
				'key' => 'event_time',
				'value' => $current_time1,
				'compare' => 'LIKE'
			)
		),*/
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'global_event_category',
				'field' => 'slug',
				'terms' => array( 'global-announcements' ),
				'include_children' => false,
				'operator' => 'NOT IN'
			)
		)
	);
	$the_query1 = new WP_Query( $args1 );
	if ( $the_query1->have_posts() ) :
		while ( $the_query1->have_posts() ) : $the_query1->the_post();
			$cal_post_event_id = get_the_ID();
			$event_date = get_field('event_date');
			$event_time = get_field('event_time');
			if($event_date == $current_date1 && $event_time == $current_time1) {
				//echo $cal_post_event_id;
				$post_author_id = $user_id;
				$type = get_field('type');
				$event_image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
				$text = wp_strip_all_tags(get_the_content());
				if($type == "facebook") {
					echo 'aa';
					if($user_id == 4) {
					$facebook_access_token = trim(get_user_meta( $post_author_id, 'facebook_access_token', true ));
					if ( ! empty( $facebook_access_token ) ) {
						if ( ! empty( $event_image ) ) {
							$data = [
								'message' => $text,
								'source'  => $fb->fileToUpload( $event_image ),
							];

							/*try {
								// Returns a `Facebook\FacebookResponse` object
								$response = $fb->post( '/me/photos', $data, $facebook_access_token );
							} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
								echo 'Graph returned an error: ' . $e->getMessage();
								exit;
							} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
								echo 'Facebook SDK returned an error: ' . $e->getMessage();
								exit;
							}*/

							$response = $fb->post( '/me/photos', $data, $facebook_access_token );

							$graphNode = $response->getGraphNode();

							$res  = $fb->post( '/me/feed', array(
								'message'           => $text,
								'attached_media[0]' => '{"media_fbid":"' . $graphNode['id'] . '"}',
							), $facebook_access_token );
							$post = $res->getGraphObject();
						} else {
							$res  = $fb->post( '/me/feed', array(
								'message' => $text
							), $facebook_access_token );
							$post = $res->getGraphObject();
						}
					}
					}
				}
				if($type == "twitter") {
					$twitter_access_token = get_user_meta( $post_author_id, 'twitter_access_token', true );
					if ( ! empty( $twitter_access_token ) ) {
						$twitter_access_token = get_user_meta($post_author_id, 'twitter_access_token', TRUE);
						$twitter_token_secret = get_user_meta($post_author_id, 'twitter_token_secret', TRUE);
						$connection = new TwitterOAuth(get_option('TWITTER_CONSUMER_KEY'), get_option('TWITTER_CONSUMER_SECRET'), $twitter_access_token, $twitter_token_secret);
						if(!empty($event_image)) {
							$media1 = $connection->upload('media/upload', ['media' => $event_image]);
							$parameters = [
								'status' => $text,
								'media_ids' => implode(',', [$media1->media_id_string, $media2->media_id_string])
							];
							$result = $connection->post('statuses/update', $parameters);
						} else {
							$statues = $connection->post("statuses/update", ["status" => $text]);
						}
					}
				}
				if($type == "pinterest") {
					$pinterest_access_token = get_user_meta( $post_author_id, 'pinterest_access_token', true );
					if ( ! empty( $pinterest_access_token ) ) {
						$ch = curl_init( 'https://api.pinterest.com/v1/me/search/boards/?query=internationalprom&access_token=' . $pinterest_access_token . '&fields=id' );
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

						$result = json_decode( curl_exec( $ch ) );
						if ( ! empty( $result->data ) ) {
							$board = $result->data[0]->id;
						} else {
							$ch = curl_init( 'https://api.pinterest.com/v1/boards/?access_token=' . $pinterest_access_token . '&fields=id' );
							curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
							curl_setopt( $ch, CURLOPT_POSTFIELDS, array(
									'name' => 'internationalprom'
								)
							);
							curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

							$result1 = json_decode( curl_exec( $ch ) );
							$board   = $result->data[0]->id;
						}
						if ( ! empty( $board ) ) {
							$ch = curl_init( 'https://api.pinterest.com/v1/pins/?access_token=' . $pinterest_access_token . '&fields=id' );
							curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
							curl_setopt( $ch, CURLOPT_POSTFIELDS, array(
									'board'     => $board,
									'note'      => $text,
									'image_url' => $event_image,
								)
							);
							curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

							$result1 = curl_exec( $ch );
						}
					}
				}
				if($type == "store_event") {
				}
				if($type == "email") {
					$api_key = get_user_meta($post_author_id, 'mailchimp_access_token', true);
					$url = get_user_meta($post_author_id, 'mailchimp_endpoint', true).'/3.0';
					$mailchimp_list = get_post_meta(get_the_ID(), 'mailchimpList', true);
					$templatehtml = get_post_meta(get_the_ID(), 'templateHtml', true);
					$templatetitle = get_post_meta(get_the_ID(), 'subjectLine', true);
					$previewtext = get_post_meta(get_the_ID(), 'previewtext', true);
					$fromname = get_post_meta(get_the_ID(), 'fromName', true);
					$replyto= get_post_meta(get_the_ID(), 'fromEmail', true);
					if ($mailchimp_list == 'istilist') {
						$istilist_email = get_user_meta($post_author_id, 'istilist_email', true);
						$istilist_password = get_user_meta($post_author_id, 'istilist_password', true);
						if (!empty($istilist_email) && !empty($istilist_password)) {
							$result = api_curl_connect('http://istilist.com/api/authorize/get_user_id/?email='.$istilist_email.'&password='.$istilist_password);
							$user_id = $result->message;
							if(!empty($user_id)) {
								$result1 = api_curl_connect( 'http://istilist.com/api/get_author_posts/?id=' . $user_id . '&post_type=shopper&count=-1' );
								if($result1->status == 'ok') {
									if(!empty($result1->posts)) {
										$emailArray = array();
										foreach($result1->posts as $shopper) {
											if($shopper->type == 'shopper') {
												$email = $shopper->custom_fields->customer_email[0];
												if(!empty($email)) {
													array_push($emailArray, $email);
												}
											}
										}
										$emailArray = array_unique($emailArray);
									}
								}
								if(!empty($emailArray)) {
									foreach ( $emailArray as $customer_email ) {
										$user_date = get_userdata( $post_author_id );
										$user_name = $user_date->display_name;
										$headers   = "From: ".$fromname." <".$replyto.">\r\n";
										$headers   .= 'Reply-to: ' . $replyto . '\r\n';
										$headers   .= "MIME-Version: 1.0\n";
										$headers   .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
										$msg       = get_post_meta(get_the_ID(), 'previewtext', true);
										wp_mail( trim($customer_email), $templatetitle, $templatehtml, $headers );
									}
								}
							}
						}
					}
				}
				if($type == "sms") {
					$istilist_email    = get_user_meta( $post_author_id, 'istilist_email', true );
					$istilist_password = get_user_meta( $post_author_id, 'istilist_password', true );
					if ( ! empty( $istilist_email ) && ! empty( $istilist_password ) ) {
						$result  = api_curl_connect( 'http://istilist.com/api/authorize/get_user_id/?email=' . $istilist_email . '&password=' . md5( '123456' ) );
						$user_id = $result->user_id;
						if ( ! empty( $user_id ) ) {
							$result1 = api_curl_connect( 'http://istilist.com/api/get_author_posts/?id=' . $user_id . '&post_type=shopper&count=-1' );
							$AccountSid = "ACda099752c56627de58d47ac69588823a";
							$AuthToken = "Formal!14688641";
							$client     = new Services_Twilio( $AccountSid, $AuthToken );
							foreach ( $result1->posts as $post ) {
								foreach ( $post->custom_fields->customer_phone as $customer_phone ) {
									if ( ! empty( $customer_phone ) ) {
										$msg = $text;
										try {
											$message = $client->account->messages->sendMessage(
												"+1 865-240-0405 ", // From a valid Twilio number
												$customer_phone, // Text this number
												$msg
											);
										} catch ( Exception $e ) {
											echo $customer_phone . 'is not a valid phone Number';
										}
									}
								}
							}
						}
					}
				}
			}
		endwhile;
	endif;
}
