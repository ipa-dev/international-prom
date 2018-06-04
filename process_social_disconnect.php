<?php /* Template Name: Social Disconnect */ ?>
<? global $user_ID;
   if (isset($_GET['social'])) {
   	switch($_GET['social']) {
   		case 'facebook':
   			delete_user_meta($user_ID, 'facebook_access_token');
   		break;
   		case 'twitter':
   			delete_user_meta($user_ID, 'twitter_access_token');
   			delete_user_meta($user_ID, 'twitter_token_secret');
   		break;
   		case 'mailchimp':
   			delete_user_meta($user_ID, 'mailchimp_access_token');
   			delete_user_meta($user_ID, 'mailchimp_endpoint');
   		break;
   		case 'pinterest':
   			delete_user_meta($user_ID, 'pinterest_access_token');
   		break;
   		case 'instagram':
   			delete_user_meta($user_ID, 'instagram_access_token');
   		break;
   		
   	}
   }
header('Location: '.get_bloginfo('url').'/edit-profile/');
?>