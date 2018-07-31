<?php /* Template Name: Edit Profile */ ?>
<?php require_once ("vendor/autoload.php");
      use Abraham\TwitterOAuth\TwitterOAuth;
?>
<?php if(is_user_logged_in()) { ?>
<?php get_header(); ?>
<?php global $user_ID; global $wpdb;?>
<?php $role = get_user_role($user_ID);
if(!session_id()) {
    session_start();
}
?>
<div id="content">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12">
                <div class="container">
                    <?php while (have_posts()) : the_post(); ?>
                    <h1><span><?php the_title(); ?></span></h1>
                    <div><?php //the_content(); ?></div>
                    <?php endwhile; ?>
                </div>
	        </div>
	    </div>
	</div>
</div>
<?php if ($role == "shopper") {  ?>
<div id="my_account">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12">
                <?php
                    if(isset($_POST['register'])){
                            global $wpdb;
                            if(!empty($_POST['pw1'])) {
                                $new_user_id = wp_update_user(
                                    array(
                                        'ID'                => $user_ID,
                                        'user_login'		=> $_POST['email_id'],
                                        'user_pass'			=> $_POST['pw1'],
                                        'user_email'		=> $_POST['email_id'],
                                        'first_name'		=> $_POST['fname'],
                                        'last_name'         => $_POST['lname'],
                                        'user_nicename'     => $_POST['email_id'],
                                    )
                                );
                            } else {
                                $new_user_id = wp_update_user(
                                    array(
                                        'ID'                => $user_ID,
                                        'user_login'		=> $_POST['email_id'],
                                        'user_email'		=> $_POST['email_id'],
                                        'first_name'		=> $_POST['fname'],
                                        'last_name'         => $_POST['lname'],
                                        'user_nicename'     => $_POST['email_id'],
                                    )
                                );
                            }




                            update_post_meta($page->ID, 'city', sanitize_text_field($_POST['city']));
                            update_post_meta($page->ID, 'phone', sanitize_text_field($_POST['phone']));
                            update_post_meta($page->ID, 'website', sanitize_text_field($_POST['website']));
                            update_post_meta($page->ID, 'country', sanitize_text_field($_POST['country']));
                            update_post_meta($page->ID, 'state', sanitize_text_field($_POST['state']));
                            update_post_meta($page->ID, 'postcode', sanitize_text_field($_POST['zip']));

                            $address = $_POST['address'].', '.$_POST['city'].', '.$_POST['state'].', United States';
                            $urlAdress = urlencode($address);
                            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$urlAddress;
                            $data = processURL($url);
                            $data = json_decode($data);
                            $lat = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                            $long = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
                            $address_array = array();
                            $address_array['address'] = $address;
                            $address_array['lat'] = $lat;
                            $address_array['lng'] = $long;
                            $new_address = serialize($address_array);

                            update_post_meta($page->ID, 'address', $new_address);

                            update_user_meta( $new_user_id, 'address', sanitize_text_field( $_POST['address'] ) );
                            //update_user_meta( $new_user_id, 'store_name', sanitize_text_field( $_POST['store_name'] ) );
                            update_user_meta( $new_user_id, 'website', sanitize_text_field( $_POST['website'] ) );
                            update_user_meta( $new_user_id, 'phone', sanitize_text_field( $_POST['phone'] ) );
                            update_user_meta( $new_user_id, 'country', sanitize_text_field( $_POST['country'] ) );
                            update_user_meta( $new_user_id, 'city', sanitize_text_field( $_POST['city'] ) );
                            update_user_meta( $new_user_id, 'state', sanitize_text_field( $_POST['state'] ) );
                            update_user_meta( $new_user_id, 'postcode', sanitize_text_field( $_POST['zip'] ) );
                            update_user_meta( $new_user_id, 'store_des', sanitize_text_field( $_POST['store_des'] ) );
                            $args = array(
                                'post_type' => 'retailer',
                                'posts_per_page' => -1,
                                'post_status' => 'publish',
                                'author' => $user_ID
                            );
                            $the_query = new WP_Query( $args );
                            if ( $the_query->have_posts() ) :
                            while ( $the_query->have_posts() ) : $the_query->the_post();
                                update_post_meta( get_the_ID(), 'author_email', $_POST['email_id'] );
                            endwhile;
                            endif;
                            wp_reset_postdata();
                            //header("Location: ".get_bloginfo('home')."/my-account/");
                            ?>
                                <p class="successMsg">Profile Updated</p>
                            <?php
                        }
                ?>
                <?php $user_info = get_userdata($user_ID); ?>
                <form id="joinus" class="registration" action="" method="post" enctype="multipart/form-data">
                    <?php if($role == 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
                	       <label for="store_name"><?php _e( 'Store name' ); ?> <!--<span class="required"></span>--></label>
                	       <input type="text" name="store_name" placeholder="" value="<?php echo get_user_meta( $user_ID, 'store_name', true); ?>" required="required" disabled="disabled"/>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="section group">
                        <div class="col span_6_of_12">
                        	<label for="reg_billing_first_name"><?php _e( 'First name' ); ?> <span class="required">*</span></label>
                        	<input type="text" name="fname" placeholder="" value="<?php echo $user_info->first_name; ?>" required="required" />
                        </div>
                        <div class="col span_6_of_12">
                	       <label for="reg_billing_last_name"><?php _e( 'Last name' ); ?> <span class="required">*</span></label>
                	       <input type="text" name="lname" placeholder="" value="<?php echo $user_info->last_name; ?>" required="required" />
                        </div>
                    </div>
                    <?php if($role == 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for=""><?php _e( 'Address (Select Location)' ); ?> <span class="required">*</span></label>
            				<div class="form-group">
                                <input type="text" required="required" name="address" value="<?php echo get_user_meta( $user_ID, 'address', true); ?>" class="placepicker form-control" data-map-container-id="collapseOne"/>
                            </div>
                            <div id="collapseOne" class="collapse">
                                <div class="placepicker-map thumbnail"></div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($role == 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
                    		<label for="city"><?php _e( 'City' ); ?> <span class="required">*</span></label>
                            <input type="text" name="city" value="<?php echo get_user_meta( $user_ID, 'city', true); ?>" />
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($role == 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <label for="address"><?php _e( 'State' ); ?> <span class="required">*</span></label>
                            <input type="text" name="state" value="<?php echo get_user_meta( $user_ID, 'state', true); ?>" />
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($role == 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <label for="zip"><?php _e( 'Zip Code' ); ?> <span class="required">*</span></label>
                            <input type="text" name="zip" value="<?php echo get_user_meta( $user_ID, 'postcode', true); ?>" />
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($role == 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <label for="address"><?php _e( 'Country' ); ?> <span class="required">*</span></label>
                            <select class="country" required="required" name="country" data-constraints="@Required @Country">
                                <option>Select Country</option>
                                <?php
                                    $url = TEMPLATEPATH.'/countries.xml';
                                    $xml = simplexml_load_file($url);
                                    foreach($xml->country as $country) {
                                ?>
                                <option <?php
                                            if($country['code'] == get_user_meta( $user_ID, 'country', true)) {
                                                echo 'selected="selected"';
                                            }
                                        ?> value="<?php echo $country['code']; ?>"><?php echo $country; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($role == 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for="reg_email"><?php _e( 'Phone Number' ); ?> <span class="required">*</span></label>
            				<input type="text" name="phone" placeholder="" value="<?php echo get_user_meta( $user_ID, 'phone', true); ?>" required="required" />
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($role == 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for="website"><?php _e( 'Website' ); ?> <span class="required">*</span></label>
            				<input type="text" name="website" placeholder="" value="<?php echo get_user_meta( $user_ID, 'website', true); ?>" required="required" />
                        </div>
                    </div>
                    <?php } ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for="reg_email"><?php _e( 'Email address' ); ?> <span class="required">*</span></label>
            				<input type="text" name="email_id" id="e1" placeholder="" required="required"<?php if($role == 'retailer') { ?> title="To enter multiple email use comma separated values"<?php } ?> value="<?php echo $user_info->user_email; ?>" />
                        </div>
                    </div>
                    <?php if($role != 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for="reg_email"><?php //_e( 'Varify Email address' ); ?> <span class="required">*</span></label>
            				<input type="text" name="email_id1" id="e2" placeholder="" required="required" value="<?php echo $user_info->user_email; ?>" oninput="validateEmail(document.getElementById('e1'), this);" onfocus="validateEmail(document.getElementById('e1'), this);" />
                        </div>
                    </div>
                    <?php } ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
        					<label for="reg_password"><?php _e( 'Password' ); ?></label>
        					<input type="password" name="pw1" id="pw1" placeholder="" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                    		<label for="reg_password"><?php _e( 'Varify Password' ); ?></label>
                    		<input type="password" id="pw2" name="pw2" class="input-text" oninput="validatePass(document.getElementById('pw1'), this);" onfocus="validatePass(document.getElementById('pw1'), this);" />
                        </div>
                    </div>
                    <?php if($role == 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for="store_des"><?php _e( 'Store Description ' ); ?></label>
            				<textarea name="store_des"><?php echo get_user_meta( $user_ID, 'store_des', true); ?></textarea>
                        </div>
                    </div>

                    <?php } ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <input type="submit" name="register" value="Update" class="submit-button" />
                        </div>
                    </div>
                </form>
	        </div>
	    </div>
	</div>
</div>
<?php } ?>
<?php if ($role == "retailer" && isset($_POST['post_id'])) { ?>
<div id="my_account">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12">
                <?php
                    if(isset($_POST['register'])){
                            global $wpdb;
                            update_post_meta($_POST['post_id'], 'city', sanitize_text_field($_POST['city']));
                            update_post_meta($_POST['post_id'], 'phone_number', sanitize_text_field($_POST['phone']));
                            update_post_meta($_POST['post_id'], 'website', sanitize_text_field($_POST['website']));
                            update_post_meta($_POST['post_id'], 'country', sanitize_text_field($_POST['country']));
                            update_post_meta($_POST['post_id'], 'state', sanitize_text_field($_POST['state']));
                            update_post_meta($_POST['post_id'], 'postcode', sanitize_text_field($_POST['zip']));

                            if ($_POST['address'].', '.$_POST['city'].', '.$_POST['state'].', '.$_POST['country'] != get_post_meta($_POST['post_id'], 'address', TRUE)) {

                            $address = $_POST['address'].', '.$_POST['city'].', '.$_POST['state'].', '.$_POST['country']; // Need to change country
                            $urlAdress = urlencode($address);
                            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$urlAddress;
                            $data = processURL($url);
                            $data = json_decode($data);
                            $lat = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                            $long = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
                            $address_array = array();
                            $address_array['address'] = $address;
                            $address_array['lat'] = $lat;
                            $address_array['lng'] = $long;
                            $new_address = $address_array;
                            update_post_meta($_POST['post_id'], 'address', $new_address);

                            }

                            update_user_meta( $new_user_id, 'store_des', sanitize_text_field( $_POST['store_des'] ) );
                            $args = array(
                                'post_type' => 'retailer',
                                'posts_per_page' => -1,
                                'post_status' => 'publish',
                                'author' => $user_ID
                            );
                            $the_query = new WP_Query( $args );
                            if ( $the_query->have_posts() ) :
                            while ( $the_query->have_posts() ) : $the_query->the_post();
                                update_post_meta( get_the_ID(), 'author_email', $_POST['email_id'] );
                            endwhile;
                            endif;
                            wp_reset_postdata();
                            //header("Location: ".get_bloginfo('home')."/my-account/");
                            ?>
                                <p class="successMsg">Profile Updated</p>
                            <?php
                        }
                ?>
                <?php $user_info = get_userdata($user_ID); ?>
                <form id="joinus" class="registration" action="" method="post" enctype="multipart/form-data">
                    <div class="section group">
                        <div class="col span_12_of_12">
                	       <label for="store_name"><?php _e( 'Store name' ); ?> <!--<span class="required"></span>--></label>
                	       <input type="text" name="store_name" placeholder="" value="<?php echo get_user_meta( $user_ID, 'store_name', true); ?>" required="required" disabled="disabled"/>
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for=""><?php _e( 'Address (Select Location)' ); ?> <span class="required">*</span></label>
            				<div class="form-group">
                                <input type="text" required="required" name="address" value="<?php  echo substr(get_post_meta($_POST['post_id'], 'address', true)['address'], 0, strpos(get_post_meta($_POST['post_id'], 'address', true)['address'], ",")); ?>" class="placepicker form-control" data-map-container-id="collapseOne"/>
                            </div>
                            <div id="collapseOne" class="collapse">
                                <div class="placepicker-map thumbnail"></div>
                            </div>
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                    		<label for="city"><?php _e( 'City' ); ?> <span class="required">*</span></label>
                            <input type="text" name="city" value="<?php echo get_post_meta( $_POST['post_id'], 'city', true); ?>" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <label for="address"><?php _e( 'State' ); ?> <span class="required">*</span></label>
                            <input type="text" name="state" value="<?php echo get_post_meta( $_POST['post_id'], 'state', true); ?>" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <label for="zip"><?php _e( 'Zip Code' ); ?> <span class="required">*</span></label>
                            <input type="text" name="zip" value="<?php echo get_post_meta( $_POST['post_id'], 'postcode', true); ?>" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <label for="address"><?php _e( 'Country' ); ?> <span class="required">*</span></label>
                            <select class="country" required="required" name="country" data-constraints="@Required @Country">
                                <option>Select Country</option>
                                <?php
                                    $url = TEMPLATEPATH.'/countries.xml';
                                    $xml = simplexml_load_file($url);
                                    foreach($xml->country as $country) {
                                ?>
                                <option <?php
                                            if($country == get_post_meta( $_POST['post_id'], 'country', true)) {
                                                echo 'selected="selected"';
                                            }
                                        ?> value="<?php echo $country; ?>"><?php echo $country; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for="reg_email"><?php _e( 'Phone Number' ); ?> <span class="required">*</span></label>
            				<input type="text" name="phone" placeholder="" value="<?php echo get_post_meta( $_POST['post_id'], 'phone_number', true); ?>" required="required" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for="website"><?php _e( 'Website' ); ?> <span class="required">*</span></label>
            				<input type="text" name="website" placeholder="" value="<?php echo get_post_meta( $_POST['post_id'], 'website', true); ?>" required="required" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for="store_des"><?php _e( 'Store Description ' ); ?></label>
            				<textarea name="store_des"><?php echo get_user_meta( $user_ID, 'store_des', true); ?></textarea>
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <input type="hidden" name="post_id" value="<?php echo $_POST['post_id']; ?>" />
                            <input type="submit" name="register" value="Update" class="submit-button" />
                        </div>
                    </div>

                </form>
	        </div>
	    </div>
	</div>
</div>
<?php } ?>
<?php if ($role == "retailer" && !isset($_POST['post_id'])) { ?>
<div id="my_account">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12">
                <?php
                    if(isset($_POST['register'])){
                            global $wpdb;
                            if(!empty($_POST['pw1'])) {
                                $new_user_id = wp_update_user(
                                    array(
                                        'ID'                => $user_ID,
                                        'user_login'		=> $_POST['email_id'],
                                        'user_pass'			=> $_POST['pw1'],
                                        'user_email'		=> $_POST['email_id'],
                                        'first_name'		=> $_POST['fname'],
                                        'last_name'         => $_POST['lname'],
                                        'user_nicename'     => $_POST['email_id'],
                                    )
                                );
                            } else {
                                $new_user_id = wp_update_user(
                                    array(
                                        'ID'                => $user_ID,
                                        'user_login'		=> $_POST['email_id'],
                                        'user_email'		=> $_POST['email_id'],
                                        'first_name'		=> $_POST['fname'],
                                        'last_name'         => $_POST['lname'],
                                        'user_nicename'     => $_POST['email_id'],
                                    )
                                );
                            }

                            update_user_meta( $new_user_id, 'store_name', sanitize_text_field( $_POST['store_name'] ) );
	                    update_user_meta( $new_user_id, 'store_des', sanitize_text_field( $_POST['store_des'] ) );

                            $args = array(
                                'post_type' => 'retailer',
                                'posts_per_page' => -1,
                                'post_status' => 'publish',
                                'author' => $user_ID
                            );
                            $the_query = new WP_Query( $args );
                            if ( $the_query->have_posts() ) :
                            while ( $the_query->have_posts() ) : $the_query->the_post();
                                update_post_meta( get_the_ID(), 'author_email', $_POST['email_id'] );
                            endwhile;
                            endif;
                            wp_reset_postdata();
                            //header("Location: ".get_bloginfo('home')."/my-account/");
                            ?>
                                <p class="successMsg">Profile Updated</p>
                            <?php

                            update_user_meta( $new_user_id, 'user_timezone', $_POST['select_timezone'] );

                            if (isset($_POST['istilist_email']) && isset($_POST['istilist_password'])) {
                            	update_user_meta($new_user_id, 'istilist_email', $_POST['istilist_email']);
                                update_user_meta($new_user_id, 'istilist_password', $_POST['istilist_password']);
                            }
                            if (isset($_POST['page_options'])) {
                            	$active_page_temp = get_user_meta($new_user_id, 'active_page', TRUE);
                            	if (empty($active_page_temp)) {
                            		add_user_meta($new_user_id, 'active_page', $_POST['page_options']);
                            	}
                            	else {
                            		update_user_meta($new_user_id, 'active_page', $_POST['page_options']);
                            	}
                            }

                        }
                ?>
                <?php $user_info = get_userdata($user_ID); ?>
                <form id="joinus" class="registration" action="" method="post" enctype="multipart/form-data">
                    <?php if($role == 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
                	       <label for="store_name"><?php _e( 'Store name' ); ?> <!--<span class="required"></span>--></label>
                	       <input type="text" name="store_name" placeholder="" value="<?php echo get_user_meta( $user_ID, 'store_name', true); ?>" required="required"/>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="section group">
                        <div class="col span_6_of_12">
                        	<label for="reg_billing_first_name"><?php _e( 'First name' ); ?> <span class="required">*</span></label>
                        	<input type="text" name="fname" placeholder="" value="<?php echo $user_info->first_name; ?>" required="required" />
                        </div>
                        <div class="col span_6_of_12">
                	       <label for="reg_billing_last_name"><?php _e( 'Last name' ); ?> <span class="required">*</span></label>
                	       <input type="text" name="lname" placeholder="" value="<?php echo $user_info->last_name; ?>" required="required" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for="reg_email"><?php _e( 'Email address' ); ?> <span class="required">*</span></label>
            				<input type="text" name="email_id" id="e1" placeholder="" required="required"<?php if($role == 'retailer') { ?> title="To enter multiple email use comma separated values"<?php } ?> value="<?php echo $user_info->user_email; ?>" />
                        </div>
                    </div>
                    <?php if($role != 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for="reg_email"><?php _e( 'Varify Email address' ); ?> <span class="required">*</span></label>
            				<input type="text" name="email_id1" id="e2" placeholder="" required="required" value="<?php echo $user_info->user_email; ?>" oninput="validateEmail(document.getElementById('e1'), this);" onfocus="validateEmail(document.getElementById('e1'), this);" />
                        </div>
                    </div>
                    <?php } ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
        					<label for="reg_password"><?php _e( 'Password' ); ?></label>
        					<input type="password" name="pw1" id="pw1" placeholder="" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                    		<label for="reg_password"><?php _e( 'Verify Password' ); ?></label>
                    		<input type="password" id="pw2" name="pw2" class="input-text" oninput="validatePass(document.getElementById('pw1'), this);" onfocus="validatePass(document.getElementById('pw1'), this);" />
                        </div>
                    </div>
                    <?php if($role == 'retailer') { ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for="store_des"><?php _e( 'Store Description ' ); ?></label>
            				<textarea name="store_des"><?php echo get_user_meta( $user_ID, 'store_des', true); ?></textarea>
                        </div>
                    </div>
                    <div class="section group">
                    	<div class="col span_12_of_12">
                    		<label for="istilist_email">iSTiLiST E-mail</label>
                    		<input type="text" id="istilist_email" name="istilist_email" class="input-text" value="<?php echo get_user_meta($user_ID, 'istilist_email', TRUE); ?>"/>

                    	</div>
                    </div>
                    <div class="section group">
                    	<div class="col span_12_of_12">
                    		<label for="istilist_password">iSTiLiST Password</label>
                    		<input type="password" id="istilist_password" name="istilist_password" class="input-text" value="" placeholder="Use this field to update your iSTiLiST password"/>
                    	</div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <label for="select_timezone">Select Timezone</label>
                            <select required="required" id="select_timezone" name="select_timezone">
                                <?php
                                $user_timezone = get_user_meta($user_ID, 'user_timezone', true);
                                $timezone_identifiers = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                                foreach($timezone_identifiers as $tz) {
                                    if($tz == $user_timezone) {
	                                    echo "<option selected value='" . $tz . "'>" . $tz . "</option>";
                                    } else {
	                                    echo "<option value='" . $tz . "'>" . $tz . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="section group">
				<div class="col span_4_of_12">
                    <?php
                    $fb = new Facebook\Facebook([
	                    'app_id' => get_option('FACEBOOK_APP_ID'), // Replace {app-id} with your app id
	                    'app_secret' => get_option('FACEBOOK_APP_SECRET'),
	                    'default_graph_version' => 'v2.2',
                    ]);
                    ?>
					<?php
					$error = 0;
                    $facebook_access_token = get_user_meta($user_ID, 'facebook_access_token', TRUE);
                    if (!empty($facebook_access_token)) {
	                    try {
		                    // Returns a `Facebook\FacebookResponse` object
		                    $response = $fb->get(
			                    '/me',
			                    $facebook_access_token
		                    );
	                    } catch ( Facebook\Exceptions\FacebookResponseException $e ) {
		                    //echo 'Graph returned an error: ' . $e->getMessage();
		                    //exit;
		                    $error = 1;
		                    goto break_free_of_try;
	                    } catch ( Facebook\Exceptions\FacebookSDKException $e ) {
		                    //echo 'Facebook SDK returned an error: ' . $e->getMessage();
		                    //exit;
		                    $error = 1;
		                    goto break_free_of_try;
	                    }
                    }
						break_free_of_try:
                        if($error != 1 && !empty($facebook_access_token)) {
                        ?>
						<span>Connected to Facebook</span>
						<a class="social_connect" href="<?php bloginfo('url'); ?>/social-disconnect/?social=facebook">Disconnect</a>
					<?php } else {

						$helper = $fb->getRedirectLoginHelper();

						$permissions = ['email', 'public_profile', 'publish_actions', 'manage_pages', 'publish_pages']; // Optional permissions
						$loginUrl = $helper->getLoginUrl(get_bloginfo('url').'/facebook-authorization/', $permissions);

					?>
						<a class="social_connect" href="<?php echo htmlspecialchars($loginUrl); ?>">Connect to Facebook</a>
					<?php } ?>
				</div>
				<div class="col span_8_of_12">
					<?php if($error != 1 && !empty($facebook_access_token)) {
						try {
							// Returns a `Facebook\FacebookResponse` object
							$response = $fb->get(
								'/me',
								$facebook_access_token
							);
						} catch(Facebook\Exceptions\FacebookResponseException $e) {
							echo 'Graph returned an error: ' . $e->getMessage();
							exit;
						} catch(Facebook\Exceptions\FacebookSDKException $e) {
							echo 'Facebook SDK returned an error: ' . $e->getMessage();
							exit;
						}
						$user = $response->getGraphUser();

						try {
						  // Returns a `Facebook\FacebookResponse` object
						  $response = $fb->get(
						    '/'.$user['id'].'/accounts',
						    $facebook_access_token
						  );
						} catch(Facebook\Exceptions\FacebookResponseException $e) {
						  echo 'Graph returned an error: ' . $e->getMessage();
						  exit;
						} catch(Facebook\Exceptions\FacebookSDKException $e) {
						  echo 'Facebook SDK returned an error: ' . $e->getMessage();
						  exit;
						}
						$active_page = get_user_meta($user_ID, 'active_page', true);
							?>
                            <span>Choose which page where you would like posts to appear:</span>
                            <select name="page_options" form="joinus">
							<?php
							if(!empty($response->getDecodedBody()["data"])) {
                                foreach ( $response->getDecodedBody()["data"] as $page_name ) {
                                    ?>
                                    <option<?php if($active_page == $page_name['id']) { echo ' selected'; } ?> value="<?php echo $page_name['id']; ?>"><?php echo $page_name['name']; ?></option>
                                    <?php
                                }
						    } else {
                            ?>
                                <option value="me">Personal Page</option>
                            <?php
                            }
                    ?>
                        </select>
                            <?php
						}
					?>

				</div>
                </div>
                <div class="section group">
				<div class="col span_3_of_12">
					<?php $mailchimp_access_token = get_user_meta($user_ID, 'mailchimp_access_token', TRUE); if (!empty($mailchimp_access_token)) { ?>

						<span>Connected to MailChimp</span>
						<a class="social_connect" href="<?php bloginfo('url'); ?>/social-disconnect/?social=mailchimp">Disconnect</a>
					<?php } else {?>

						<a class="social_connect" href="https://login.mailchimp.com/oauth2/authorize?response_type=code&client_id=<?php echo get_option('MAILCHIMP_CLIENT_ID'); ?>&redirect_uri=<?php echo urlencode(get_option('MAILCHIMP_REDIRECT_URI')); ?>">Connect to MailChimp</a>

					<?php } ?>
				</div>
				<div class="col span_3_of_12">
					<?php $twitter_access_token = get_user_meta($user_ID, 'twitter_access_token', TRUE); if (!empty($twitter_access_token)) { ?>
						<span>Connected to Twitter</span>
						<a class="social_connect" href="<?php bloginfo('url'); ?>/social-disconnect/?social=twitter">Disconnect</a>
					<?php } else {
						$connection = new TwitterOAuth(get_option('TWITTER_CONSUMER_KEY'), get_option('TWITTER_CONSUMER_SECRET'));
						$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => get_option('TWITTER_CALLBACK_URL')));
						$_SESSION['oauth_token'] = $request_token['oauth_token'];
						$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
						$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
					?>
						<a class="social_connect" href="<?php echo $url; ?>">Connect to Twitter</a>
					<?php } ?>
				</div>
				<div class="col span_3_of_12">
					<?php $pinterest_access_token = get_user_meta($user_ID, 'pinterest_access_token', TRUE); if (!empty($pinterest_access_token)) { ?>
						<span>Connected to Pinterest</span>
						<a class="social_connect" href="<?php bloginfo('url'); ?>/social-disconnect/?social=pinterest">Disconnect</a>
					<?php } else { ?>
						<a class="social_connect" href="https://api.pinterest.com/oauth/?response_type=code&client_id=<?php echo get_option('PINTEREST_APP_ID'); ?>&scope=read_public,write_public&redirect_uri=<?php echo urlencode(get_option('PINTEREST_REDIRECT_URI')); ?>">Connect to Pinterest</a>
					<?php } ?>
				</div>
				<div class="col span_3_of_12">
					<?php $instagram_access_token = get_user_meta($user_ID, 'instagram_access_token', TRUE); if (!empty($instagram_access_token)) { ?>
						<span>Connected to Instagram</span>
						<a class="social_connect" href="<?php bloginfo('url'); ?>/social-disconnect/?social=instagram">Disconnect</a>
					<?php } else { ?>
						<a class="social_connect" href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo get_option('INSTAGRAM_CLIENT_ID'); ?>&redirect_uri=<?php echo get_option('INSTAGRAM_REDIRECT_URI'); ?>&response_type=code">Connect to Instagram</a>
					<?php } ?>
				</div>
		    </div>
                    <?php } ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <input type="submit" name="register" value="Update" class="submit-button" />
                        </div>
                    </div>
                </form>
	        </div>
	    </div>
	</div>
</div>

<?php } ?>
<?php get_footer(); ?>
<?php } else {
   header('Location: '.get_bloginfo('home').'/login');
} ?>
