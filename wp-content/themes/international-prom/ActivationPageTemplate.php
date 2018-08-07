<?php /* Template Name: Activation Template */ ?>
<?php get_header(); ?>
<div id="title">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12"> 
                <h1><?php the_title(); ?></h1>                
	        </div>
	    </div>
	</div>
</div>
<div id="content">
    <div class="maincontent noPadding">
        <div class="section group">
            <div class="col span_12_of_12">
                <?php if(!is_user_logged_in()){ 
                    global $wpdb;
                    $user_status = 2;
                    $key = $_GET['key'];
                    $wpdb->update($wpdb->users, array('user_status' => $user_status), array('user_activation_key' => $key));
                    echo '<p class="successMsg"><strong>Congratulations</strong><br /><br />Your account has been activated. Click here to <strong><a href="'.get_bloginfo('home').'/sign-in">Sign In</a></strong></p>';
                ?>
                <?php } ?>   
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>