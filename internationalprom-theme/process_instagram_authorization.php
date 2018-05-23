<?php /* Template Name: Instagram Authorization */ ?>
<?php
global $user_ID;
$ch = curl_init('https://api.instagram.com/oauth/access_token');                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, array(
	'grant_type' => 'authorization_code',
	'client_id' => get_option('INSTAGRAM_CLIENT_ID'),
	'client_secret' => get_option('INSTAGRAM_CLIENT_SECRET'),
	'code' => $_GET['code'],
	'redirect_uri' => get_option('INSTAGRAM_REDIRECT_URI')
	)
);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);           

$result = curl_exec($ch);
$result_arr = json_decode($result, TRUE);

$instagram_access_token = get_user_meta($user_ID, 'instagram_access_token', TRUE);
if (empty($instagram_access_token)) {
	add_user_meta($user_ID, 'instagram_access_token', $result_arr['access_token']);
}
else {
	update_user_meta($user_ID, 'instagram_access_token', $result_arr['access_token']);
}
header("Location: ".get_bloginfo('url')."/edit-profile/");
?>