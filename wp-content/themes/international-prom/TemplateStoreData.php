<?php /* Template Name: Store Information */ ?>
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
<?php $store_id = get_user_meta( $user_ID, 'store_id', true ); ?>
<div id="my_acc">
	<div class="maincontent">
	    <div class="section group">
        <?php 
            if(isset($_POST['store_submit'])) {
                $post = array(
                    'ID' => $store_id,
                    'post_title' => $_POST['rname'],
                    'post_type' => 'retailer',
                    'post_content' => $_POST['des'],
                    'post_author' => $user_ID                                
                ); 
                wp_update_post($post);
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
                        
                update_post_meta($store_id, 'address', $new_address);
                update_post_meta($store_id, 'phone_number', $_POST['phone']);
                echo "<div class='col span_12_of_12'><p class='successMsg'>Store Information updated...</p></div>";
            }
        ?>
	        <div class="col span_12_of_12">
                <form id="joinus" class="registration" action="" method="POST" enctype="multipart/form-data">
                    <div class="section group">
                        <div class="col span_12_of_12">
                        	<label><?php _e( 'Name' ); ?> <span class="required">*</span></label>
                        	<input type="text" name="rname" placeholder="" value="<?php echo get_the_title($store_id); ?>" required="required" />
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                        	<label>Description *</label>
                            <?php $content_event = get_post($store_id); ?>
                            <textarea name="des" ><?php echo $content_event->post_content; ?></textarea>
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
            				<label for=""><?php _e( 'Select Location' ); ?> <span class="required">*</span></label>
            				<div class="form-group">
                                <?php $address = unserialize(get_post_meta($store_id, 'address', true)); ?>
                                <input type="text" name="address" value="<?php echo $address['address']; ?>" class="placepicker form-control" data-map-container-id="collapseOne"/>
                            </div>
                            <div id="collapseOne" class="collapse">
                                <div class="placepicker-map thumbnail"></div>
                            </div>
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