<?php /* Template Name: Cronjob Post */ ?>
<?php
require_once ("vendor/autoload.php");
$fb = new Facebook\Facebook( [
	'app_id'                => get_option( 'FACEBOOK_APP_ID' ), // Replace {app-id} with your app id
	'app_secret'            => get_option( 'FACEBOOK_APP_SECRET' ),
	'default_graph_version' => 'v2.2',
] );
use Abraham\TwitterOAuth\TwitterOAuth;
use Twilio\Rest\Client;

$tz = get_option('timezone_string');
if (empty($tz)) {
	$tz = 'America/New_York';
}
date_default_timezone_set($tz);
$current_date = date('Y-m-d');
$current_time = date('g:i A');
$post_args = array(
	'post_type' => 'cal_post_event',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'meta_query' => array(
		array(
			'key' => 'event_date',
			'value' => $current_date,
			'compare' => '='
		),
		'relation' => 'AND',
		array(
			'key' => 'event_time',
			'value' => $current_time,
			'compare' => 'LIKE'
		)
	)
);

$cal_post_event_data = new WP_Query($post_args);

if($cal_post_event_data->have_posts()){
	while($cal_post_event_data->have_posts()) : $cal_post_event_data->the_post();

		$cal_post_event_id = get_the_ID();
		$post_author_id = get_post_field( 'post_author', $cal_post_event_id );
		$type = get_post_meta($cal_post_event_id, 'type', true);
		$event_image = get_post_meta(get_the_ID(), 'event_image', true);
		$text = wp_strip_all_tags(get_the_content());
		switch ($type) {
			case "facebook":
				$facebook_access_token = get_user_meta($post_author_id, 'facebook_access_token', TRUE);
				$active_page = get_user_meta($post_author_id, 'active_page', TRUE);
				if ( $active_page != 'me') {
					$response = $fb->get('/'.$active_page.'?fields=access_token', $facebook_access_token);
					$body = $response->getDecodedBody();
					$facebook_access_token = $body['access_token'];
				}
				if (!empty($facebook_access_token)) {
					if(!empty($event_image)) {
						$data = [
							'message' => $text,
							'source'  => $fb->fileToUpload( $event_image ),
						];

						try {
							// Returns a `Facebook\FacebookResponse` object
							$response = $fb->post( '/'.$active_page.'/photos', $data, $facebook_access_token );
						} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
							echo 'Graph returned an error: ' . $e->getMessage();
							exit;
						} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
							echo 'Facebook SDK returned an error: ' . $e->getMessage();
							exit;
						}

						$graphNode = $response->getGraphNode();

						$res  = $fb->post( $active_page.'/feed', array(
							'message'           => $text,
							'attached_media[0]' => '{"media_fbid":"' . $graphNode['id'] . '"}',
						), $facebook_access_token );
						$post = $res->getGraphObject();
					} else {

						$res  = $fb->post( '/'.$active_page.'/feed', array(
							'message'           => wp_strip_all_tags(get_the_content())
						), $facebook_access_token );
						$post = $res->getGraphObject();
					}
				}
				break;
			case "twitter":
				$twitter_access_token = get_user_meta($post_author_id, 'twitter_access_token', TRUE);
				if (!empty($twitter_access_token)) {
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
						$result = $connection->post("statuses/update", ["status" => $text]);
					}
					print_r($result);
				}
				break;
			case "pinterest":
				$pinterest_access_token = get_user_meta($post_author_id, 'pinterest_access_token', true);
				if (!empty($pinterest_access_token)) {
					$board = get_post_meta($cal_post_event_id, 'pinboard', true);
					/*if(empty($board)) {
						$board = 'internationalprom';
					}
					$ch = curl_init( 'https://api.pinterest.com/v1/me/search/boards/?query='.$board.'&access_token=' . $pinterest_access_token . '&fields=id' );
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
					}*/
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
				break;
			case "store_event":
				break;
			case "email":
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
								//Check for existing mailchimp list named istilist
								$result = mailchimp_curl_connect($url.'/lists', 'GET', $api_key, array('count' => '30'));
								$result = json_decode($result);
								foreach($result['lists'] as $list) {
									if ($list['name'] == 'istilist') {
										//remove old list
										mailchimp_curl_connect($url.'/lists/'.$list['id'], 'DELETE', $api_key);
									}
								}
								//Create List
								$store_name = get_user_meta($post_author_id, 'store_name', true);
								$store_address = get_user_meta($post_author_id, 'store_address', true);
								$store_city = get_user_meta($post_author_id, 'store_city', true);
								$store_state = get_user_meta($post_author_id, 'store_state', true);
								$store_zip = get_user_meta($post_author_id, 'store_zip', true);

								$list_data = array(
									'name' => 'istilist',
									'contact' => array(
										'company' => $store_name,
										'address1' => $store_address,
										'city' => $store_city,
										'state' => $store_state,
										'zip' => $store_zip,
									),
									'permission_reminder' => 'You signed up for updates at our store',
									'campaign_defaults' => array(
										'from_name' => $fromname,
										'from_email' => $replyto,
										'subject' => $store_name,
										'language' => 'English',
									),
									'email_type_option' => 'false',
								);

								//New list object
								$result = mailchimp_curl_connect($url.'/lists/', 'POST', $api_key, $list_data);

								//Add members to new list
								foreach ( $emailArray as $customer_email ) {
									mailchimp_curl_connect($url.'/lists/'.$result['id'], 'POST', $api_key, array('email_address' => $customer_email, 'status' => 'subscribed'));
									//$user_date = get_userdata( $post_author_id );
									// $user_name = $user_date->display_name;
									// $headers   = "From: ".$fromname." <".$replyto.">\r\n";
									// $headers   .= 'Reply-to: ' . $replyto . '\r\n';
									// $headers   .= "MIME-Version: 1.0\n";
									// $headers   .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
									// $msg       = get_post_meta(get_the_ID(), 'previewtext', true);
									//wp_mail( trim($customer_email), $templatetitle, $templatehtml, $headers );
								}
								$mailchimp_list = $result['id'];
							}
						}
					}
				}
				if ($mailchimp_list == 'istilist') exit();
					//1. Create Template
					$template_url = $url.'/templates';
					$data_string = json_encode(array(
							'name'     => $templatetitle,
							'html' => $templatehtml,
					));
					$ch = curl_init( $template_url );
					curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
					curl_setopt($ch, CURLOPT_USERPWD, "apikey:$api_key");
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					    'Content-Type: application/json',
					    'Content-Length: ' . strlen($data_string))
					);
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

					$result1 = curl_exec( $ch );

					//2. Collect Template ID

					$result1 = json_decode($result1, TRUE);
					$templateID = $result1['id'];
					//3. Create Campaign
					$campaign_url = $url.'/campaigns';
					$data_string = json_encode(array(
						'type' => 'regular',
						'recipients' => array(
							'list_id' => $mailchimp_list,
						),
						'settings' => array(
							'subject_line' => $templatetitle,
							'preview_text' => $previewtext,
							'from_name' => $fromname,
							'reply_to' => $replyto,
							'template_id' => $templateID,
						),

					));

					$ch = curl_init( $campaign_url );
					curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
					curl_setopt($ch, CURLOPT_USERPWD, "apikey:$api_key");
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					    'Content-Type: application/json',
					    'Content-Length: ' . strlen($data_string))
					);
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
					$result1 = curl_exec( $ch );
					$result1 = json_decode($result1, TRUE);
					$campaign_id = $result1['id'];
					//Send E-mail
					$send_url = $url.'/campaigns/'.$campaign_id.'/actions/send';
					$ch = curl_init( $send_url );
					curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
					curl_setopt($ch, CURLOPT_USERPWD, "apikey:$api_key");
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
					$result1 = curl_exec( $ch );
				
				break;
			case "sms":
				$istilist_email = get_user_meta($post_author_id, 'istilist_email', true);
				$istilist_password = get_user_meta($post_author_id, 'istilist_password', true);
				if (!empty($istilist_email) && !empty($istilist_password)) {
					$result = api_curl_connect('http://istilist.com/api/authorize/get_user_id/?email='.$istilist_email.'&password='.$istilist_password);
					$user_id = $result->message;
					if(!empty($user_id)) {
						$result1 = api_curl_connect( 'http://istilist.com/api/get_author_posts/?id=' . $user_id . '&post_type=shopper&count=-1' );
						if($result1->status == 'ok') {
							if(!empty($result1->posts)) {
								$phoneArray = array();
								foreach($result1->posts as $shopper) {
									if($shopper->type == 'shopper') {
										$phone = $shopper->custom_fields->customer_phone[0];
										if(!empty($phone)) {
											array_push($phoneArray, $phone);
										}
									}
								}
								$phoneArray = array_unique($phoneArray);
							}
						}
						$AccountSid = "ACdb92d82faf7befbb1538a208224133a4";
						$AuthToken = "1859b70bd4b570f6c8ff702b1ffd005d";
						$client = new Client($AccountSid, $AuthToken);
						if(!empty($phoneArray)) {
							foreach ( $phoneArray as $customer_phone ) {
								if ( ! empty( $customer_phone ) ) {
									$sms = $client->account->messages->create(
										$customer_phone,
										array(
											'from' => '+18652400405',
											'body' => $text
										)
									);
									echo "success";
								}
							}
						}
					}
				}
				break;
			default:
		}
	endwhile;
}
//wp_reset_post_data();

$tz = get_option('timezone_string');
date_default_timezone_set($tz);
$current_date1 = date('Y-m-d');
$current_time1 = date('g:i A');

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
		//'p' => 38414,
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
			array(
				'taxonomy' => 'global_event_category',
				'field' => 'slug',
				'terms' => array( 'global-share', 'global-suggestions' ),
				'include_children' => false,
				'operator' => 'IN'
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
					//echo 'aa';
						$active_page = get_user_meta($post_author_id, 'active_page', TRUE);
						$facebook_access_token = get_user_meta( $post_author_id, 'facebook_access_token', true );

						if ( ! empty( $facebook_access_token ) && ! empty($active_page)) {
							if ( $active_page != 'me ') {
								$response = $fb->get('/'.$active_page.'?fields=access_token', $facebook_access_token);
								$body = $response->getDecodedBody();
								$facebook_access_token = $body['access_token'];
							}
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

								$response = $fb->post( '/'.$active_page.'/photos', $data, $facebook_access_token );

								$graphNode = $response->getGraphNode();

								$res  = $fb->post( '/'.$active_page.'/feed', array(
									'message'           => $text,
									'attached_media[0]' => '{"media_fbid":"' . $graphNode['id'] . '"}',
								), $facebook_access_token );
								$post = $res->getGraphObject();
							} else {
								$res  = $fb->post( '/'.$active_page.'/feed', array(
									'message' => $text
								), $facebook_access_token );
								$post = $res->getGraphObject();
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
						var_dump($result);
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
							var_dump($result1);
							$board   = $result->data[0]->id;
							var_dump($board);
						}
						if ( ! empty( $board ) ) {
							echo "test";
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
					$istilist_email = get_user_meta($post_author_id, 'istilist_email', true);
					$istilist_password = get_user_meta($post_author_id, 'istilist_password', true);

					$api_key = get_user_meta($post_author_id, 'mailchimp_access_token', true);
					$url = get_user_meta($post_author_id, 'mailchimp_endpoint', true).'/3.0/lists/';

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
									$from      = $user_date->user_email;
									$subject   = get_post_meta(get_the_ID(), 'subjectLine', true);
									$headers   = "From: ".get_post_meta(get_the_ID(), 'fromName', true)." <".get_post_meta(get_the_ID(), 'fromEmail', true).">\r\n";
									$headers   .= 'Reply-to: ' . get_post_meta(get_the_ID(), 'fromEmail', true) . '\r\n';
									$headers   .= "MIME-Version: 1.0\n";
									$headers   .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
									$msg       = get_post_meta(get_the_ID(), 'previewtext', true);
									wp_mail( trim($customer_email), $subject, $msg, $headers );
									$data = array(
										"email_address" => trim($customer_email),
										"status" => "subscribed"
									);
									$result = json_decode( mailchimp_curl_connect( $url, 'GET', $api_key, $data) );
								}
							}
						}
					}
				}
				if($type == "sms") {
					echo "test";
					exit();
					$istilist_email = get_user_meta($post_author_id, 'istilist_email', true);
					$istilist_password = get_user_meta($post_author_id, 'istilist_password', true);
					if (!empty($istilist_email) && !empty($istilist_password)) {
						$result = api_curl_connect('http://istilist.com/api/authorize/get_user_id/?email='.$istilist_email.'&password='.$istilist_password);
						$user_id = $result->message;
						if(!empty($user_id)) {
							$result1 = api_curl_connect( 'http://istilist.com/api/get_author_posts/?id=' . $user_id . '&post_type=shopper&count=-1' );
							if($result1->status == 'ok') {
								if(!empty($result1->posts)) {
									$phoneArray = array();
									foreach($result1->posts as $shopper) {
										if($shopper->type == 'shopper') {
											$phone = $shopper->custom_fields->customer_phone[0];
											if(!empty($phone)) {
												array_push($phoneArray, $phone);
											}
										}
									}
									$phoneArray = array_unique($phoneArray);
								}
							}
							$AccountSid = "ACdb92d82faf7befbb1538a208224133a4";
							$AuthToken = "1859b70bd4b570f6c8ff702b1ffd005d";
							$client = new Client($AccountSid, $AuthToken);
							if(!empty($phoneArray)) {
								foreach ( $phoneArray as $customer_phone ) {
									if ( ! empty( $customer_phone ) ) {
										var_dump($customer_phone);
										/*$sms = $client->account->messages->create(
											$customer_phone,
											array(
												'from' => '+18652400405',
												'body' => $text
											)
										);*/
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
