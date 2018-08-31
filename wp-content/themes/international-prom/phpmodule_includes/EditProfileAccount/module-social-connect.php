<?php
require_once dirname( __FILE__ ) . '/../../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
( new \Dotenv\Dotenv( __DIR__ . '/../../' ) )->load();
?>
<div class="section group">
	<?php require_once 'module_social_connect/module-facebook.php'; ?>
</div>
<div class="section group">
	<div class="col span_6_of_12">
		<?php require_once 'module_social_connect/module-mailchimp.php'; ?>
	</div>
	<div class="col span_6_of_12">
		<?php require_once 'module_social_connect/module-twitter.php'; ?>
	</div>
</div>
<div class="section group">
	<div class="col span_6_of_12">
		<?php require_once 'module_social_connect/module-pinterest.php'; ?>
	</div>
	<div class="col span_6_of_12">
		<?php require_once 'module_social_connect/module-instagram.php'; ?>
	</div>
</div>
