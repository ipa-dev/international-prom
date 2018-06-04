<?php /* Template Name: Istilist Email Blast */ ?>
<?php if(is_user_logged_in()) { ?>
<?php get_header(); ?>
<?php global $user_ID; global $wpdb; ?>
<?php $user_info = get_userdata($user_ID); ?>
<?php $role = get_user_role($user_ID); ?>
<?php
	$istilist_retailer_id = get_user_meta($user_ID, 'istilist_id', TRUE);
	$istilist_email = get_user_meta($user_ID, 'istilist_email', TRUE); 
	if (empty($istilist_retailer_id) && !empty($istilist_email)) {
		$istilist_password = get_user_meta($user_ID, 'istilist_password', TRUE); 
		$url = "http://istilist.com/api/authorize/get_user_id/?email=".$istilist_email."&password=".$istilist_password;
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		
		$result = curl_exec($ch);
		
		curl_close($ch);
		
		$result_assoc = json_decode($result, TRUE);
		add_user_meta($user_ID, 'istilist_id', $result_assoc['message']); 
	}
	//Do search for list named iSTiLiST and if exists use edit instead of create
	$api_endpoint = get_user_meta($user_ID, 'mailchimp_endpoint', TRUE);
	$data = '
		{
			"name": "iSTiLiST",
			
		}
	
	';
	
	$ch = curl_init();
	
	
	
	curl_setopt($ch, CURLOPT_URL, $api_endpoint.'/3.0/lists');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
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
<div id="istilist_blast">
	<div class="section group">
		
	</div>
</div>
<?php get_footer(); ?>
<?php } else {
   header('Location: '.get_bloginfo('home').'/sign-in'); 
} ?>