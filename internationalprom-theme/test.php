<?php /* Template Name: Test */ ?>
<?php get_header(); ?>
<div id="content">
<div class="maincontent">
<?php
	
	print_r(openssl_get_cipher_methods()); 
?>
</div>
</div>