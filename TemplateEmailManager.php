<?php /* Template Name: Email Manager Template */ ?>
<?php if(is_user_logged_in()) { ?>
<?php get_header(); ?>
<?php global $user_ID; global $wpdb; ?>
<?php $user_info = get_userdata($user_ID); ?>
<?php $role = get_user_role($user_ID); ?>
<?php
	if (isset($_GET['code'])) {
		$url = 'https://login.mailchimp.com/oauth2/token';
		$fields = array(
			'code' => urlencode($_GET['code']),
			'grant_type' => urlencode('authorization_code'),
			'client_id' => urlencode(get_option("MAILCHIMP_CLIENT_ID")),
			'client_secret' => urlencode(get_option("MAILCHIMP_CLIENT_SECRET")),
			'redirect_uri' => urlencode(get_option("MAILCHIMP_REDIRECT_URI")),
		);
		
		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		$fields_string = rtrim($fields_string, "&");

		//open connection
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		//execute post
		$result = curl_exec($ch);
		//close connection
		curl_close($ch);
		
		
		
		$result_assoc = json_decode($result, TRUE);
		
		$access_token = get_user_meta($user_ID, 'mailchimp_access_token', TRUE);
		if (empty($access_token)) {
			add_user_meta($user_ID, 'mailchimp_access_token', $result_assoc['access_token']);
		} else {
			update_user_meta($user_ID, 'mailchimp_access_token', $result_assoc['access_token']);
		}
		
		$url = 'https://login.mailchimp.com/oauth2/metadata';
		$header = array();
		$header[] = 'User-Agent: oauth2-draft-v10';
		$header[] = 'Host: login.mailchimp.com';
		$header[] = 'Accept: application/json';
		$header[] = 'Authorization: OAuth '.$result_assoc['access_token'];
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		
		$result = curl_exec($ch);
		$result_assoc = json_decode($result, TRUE);
		
		curl_close($ch);
		
		$api_endpoint = get_user_meta($user_ID, 'mailchimp_endpoint', TRUE);
		if (empty($api_endpoint)) {
			add_user_meta($user_ID, 'mailchimp_endpoint', $result_assoc['api_endpoint']); 
		} else {
			update_user_meta($user_ID, 'mailchimp_endpoint', $result_assoc['api_endpoint']);
		}
	}
	
?>
<div id="content">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12"> 
                <div class="container">
                    <?php while (have_posts()) : the_post(); ?>
                    <h1><span><?php if($role == 'retailer') { ?>Retailer Account Information<?php } else { ?>Shopper Account Information<?php } ?></span></h1>
                    <div><?php //the_content(); ?></div>
                    <?php endwhile; ?>  
                </div>                         
	        </div>
	    </div>
	</div>
</div>
<div id="email_manager">
	<?php
		$access_token = get_user_meta($user_ID, 'mailchimp_access_token', TRUE);
		if (empty($access_token)) {
	?>
			<div class="section group">
				<div class="col span_4_of_12"></div>
				<div class="col span_4_of_12">
					<a class="custom_button" href="https://login.mailchimp.com/oauth2/authorize?response_type=code&client_id=<?php echo get_option('MAILCHIMP_CLIENT_ID'); ?>&redirect_uri=<?php echo urlencode(get_option('MAILCHIMP_REDIRECT_URI')); ?>">Connect to MailChimp Account</a>
				</div>
				<div class="col span_4_of_12"></div>
			</div>	
	<?php
		} if (!empty($access_token)) {
	?>
			<div class="section group">
				<div class="col span_4_of_12"></div>
				<div class="col span_4_of_12">
					<h4>Please select a list from below to create a campaign, or select iSTiLiST to send an e-mail blast out to shoppers registered from your store</h4>
				</div>
				<div class="col span_4_of_12"></div>
			</div>
	<?php
			$api_endpoint = get_user_meta($user_ID, 'mailchimp_endpoint', TRUE);
			$header = array();
			/*$header[] = 'User-Agent: oauth2-draft-v10';
			$header[] = 'Host: '.$api_endpoint;
			$header[] = 'Accept: application/json'; */
			$header[] = 'Authorization: OAuth '.$access_token;
			
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $api_endpoint.'/3.0/lists?count=30');
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			
			$result = curl_exec($ch); 
			$result_assoc = json_decode($result, TRUE);
			foreach ($result_assoc['lists'] as $list) {
	?>
				<div class="section group">
					<div class="col span_5_of_12"></div>
					<div class="col span_2_of_12">
						<?php echo $list['name']; ?>
					</div>
					<div class="col span_5_of_12"></div>
				</div>
	<?php
			}
	?>
			<div class="section group">
				<div class="col span_5_of_12"></div>
				<div class="col span_2_of_12">
					<a href="http://internationalprom.com/istilist-email-blast/">iSTiLiST</a>
				</div>
				<div class="col span_5_of_12"></div>
			</div>
	<?php 
		}
	?>
	
</div>
<?php get_footer(); ?>
<?php } else {
   header('Location: '.get_bloginfo('home').'/sign-in'); 
} ?>