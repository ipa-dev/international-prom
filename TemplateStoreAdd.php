<?php /* Template Name: Store Add */ ?>
<?php if(is_user_logged_in()) { ?>
<?php get_header(); ?>
<?php global $user_ID; ?>
<?php $user_info = get_userdata($user_ID); ?>
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
<div id="my_acc">
	<div class="maincontent">
	    <div class="section group">
        <?php 
            if(isset($_POST['store_submit'])) {
                $post = array(
                    'post_title' => $_POST['rname'],
                    'post_type' => 'retailer',
                    'post_status' => 'pending',                    
                    'post_content' => $_POST['des'],
                    'post_author' => $user_ID                                
                ); 
                $new_store = wp_insert_post( $post );
                $address = urlencode($_POST['address']);
                $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address;
                $data = processURL($url);
                $data = json_decode($data);
                $lat = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                $long = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}; 
                $address_array = array();
                $address_array['address'] = $_POST['address'];    
                $address_array['lat'] = $lat;
                $address_array['lng'] = $long;  
                
                $new_address = serialize($address_array);
                        
                add_post_meta( $new_store, 'address', $new_address );
                add_post_meta( $new_store, 'country', sanitize_text_field( $_POST['country'] ) ); 
                add_post_meta( $new_store, 'city', sanitize_text_field( $_POST['city'] ) );   
                add_post_meta( $new_store, 'state', sanitize_text_field( $_POST['state'] ) );
                add_post_meta( $new_store, 'postcode', sanitize_text_field( $_POST['zip'] ) );
                add_post_meta( $new_store, 'phone_number', $_POST['phone'] );
                add_post_meta( $new_store, 'website', sanitize_text_field( $_POST['website'] ) );
                add_post_meta( $new_store, 'author_email', $user_info->user_email );
                echo "<div class='col span_12_of_12'><p class='successMsg'>Store Added...</p></div>";
            }
        ?>
	        <div class="col span_12_of_12">
                <form id="joinus" class="registration" action="" method="POST" enctype="multipart/form-data">
                    <div class="section group">
                        <div class="col span_12_of_12">
                        	<label><?php _e( 'Store Name' ); ?> <span class="required">*</span></label>
                        	<input type="text" name="rname" placeholder="" value="" required="required" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for=""><?php _e( 'Address (Select Location)' ); ?> <span class="required">*</span></label>
            				<div class="form-group">
                                <input type="text" name="address" value="" class="placepicker form-control" data-map-container-id="collapseOne"/>
                            </div>
                            <div id="collapseOne" class="collapse">
                                <div class="placepicker-map thumbnail"></div>
                            </div>
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                    		<label for="city"><?php _e( 'City' ); ?> <span class="required">*</span></label>
                            <input type="text" name="city" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <label for="address"><?php _e( 'State' ); ?> <span class="required">*</span></label>
                            <input type="text" name="state" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <label for="zip"><?php _e( 'Zip Code' ); ?> <span class="required">*</span></label>
                            <input type="text" name="zip" />
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
                                <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for=""><?php _e( 'Phone Number' ); ?> <span class="required">*</span></label>
            				<input type="text" name="phone" value="<?php echo get_post_meta($store_id, 'phone_number', true); ?>" placeholder="" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for="website"><?php _e( 'Website' ); ?> <span class="required">*</span></label>
            				<input type="text" name="website" placeholder="" required="required" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                        	<label><?php _e( 'Store Description ' ) ?></label>
                            <textarea name="des" ></textarea>
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <input type="submit" name="store_submit" value="Submit" class="submit-button" />
                        </div>
                    </div>
                </form>                         
	        </div>
	    </div>
	</div>
</div>
<?php get_footer(); ?>
<?php } else {
   header('Location: '.get_bloginfo('home').'/sign-in'); 
} ?>