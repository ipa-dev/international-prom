<?php /* Template Name: Store Locator */ ?>
<?php get_header(); ?>
<div id="content">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12"> 
                <div class="container">
                    <?php while (have_posts()) : the_post(); ?>
                    <h1><?php the_title(); ?></h1>
                    <div><?php the_content(); ?></div>
                    <?php endwhile; ?>  
                </div>                         
	        </div>
	    </div>
	</div>
</div>
<div id="store_locator">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12">
                <form method="POST" action="">
                    <div class="section group">
                        <div class="col span_2_of_12">
                            <label>Country</label>
                            <select class="country" name="country">
                                <option value="">Select Country</option>
                                <?php 
                                    $url = TEMPLATEPATH.'/countries.xml';
                                    $xml = simplexml_load_file($url);
                                    foreach($xml->country as $country) {
                                ?>
                                <option<?php if($country == $_SESSION['COUNTRY']) { echo ' selected="selected"'; } ?> value="<?php echo $country['code']; ?>"><?php echo $country; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col span_2_of_12">
                            <label>City</label>
                            <input type="text" name="city" />
                        </div>
                        <div class="col span_2_of_12">
                            <label>State</label>
                            <input type="text" name="state" />
                        </div>
                        <div class="col span_2_of_12">
                            <label>Zip</label>
                            <input type="text" name="zip" />
                        </div>
                        <div class="col span_2_of_12">
                            <label>Radius</label>
                            <select name="radius">
                                <option value="75">-- Select Miles --</option>
                                <option value="25">25 miles</option>
                                <option value="50">50 miles</option>
                                <option value="75">75 miles</option>
                            </select>
                        </div>
                        <div class="col span_2_of_12">
                            <label>&nbsp;&nbsp;</label>
                            <input type="submit" value="Search" name="search_location" />
                        </div>
                    </div>
                </form> 
                <hr />
                <div class="stores">
                <div class="section group">
                <?php
                    if(isset($_POST['search_location'])) {
                        $radius = $_POST['radius'];
                        $radius = $radius * 1609.34;
                        $origin = $_POST['city'].', '.$_POST['state'].', '.$_POST['zip'].', '.$_POST['country'];
                        $args = array(
                            'post_type' => 'retailer',
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'orderby' => 'title',
                            'order' => 'ASC'
                        );
                        $the_query = new WP_Query( $args );
                        if ( $the_query->have_posts() ) :
                        $i = 0;
                        $hit_array = new stdClass();
                        $destination = array();
                        $id_array = array();
                        while ( $the_query->have_posts() ) : $the_query->the_post();
                            $location = get_field('address');
                            if(!empty($location['address'])) {
                                $address = substr($location['address'], 0, strrpos($location['address'], ','));
                            }
                            array_push($destination, $address);
                            array_push($id_array, get_the_ID());
			endwhile;
                        endif;
                        $data = processURL('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.urlencode($origin).'&destinations='.urlencode(implode("|", $destination)).'&mode=driving&language=en-EN&units=imperial&sensor=false&key=AIzaSyBFhNGeLMvFXt6nyjbh_FazL-yB5OnFGug');
                            $result = json_decode($data, true);
                            for ($k = 0; $k < count($destination);$k++) {
                            	$dis = $result['rows'][0]['elements'][$k]['distance']['value'];
                                $status = $result['rows'][0]['elements'][$k]['status'];
                                $d = $dis;
                                if($status != 'ZERO_RESULTS') {
                                    if($d < $radius) {
                                    	$hit_array->{(string)$id_array[$k]} = $d;
                               
                                    	$i++;
                                    }
                                }
                            }
                            
                        $hit_array = (array) $hit_array;
                        asort($hit_array);
                        foreach ($hit_array as $key=>$val) {
                        	$key = (int)$key;
                        	 ?>
                            	<div class="col span_1_of_3">
                                            <div class="single_store matchheight">
                                                <h3><a href="<?php get_the_permalink($key); ?>"><?php echo get_the_title($key); ?></a></h3>
                                                <?php if(has_post_thumbnail($key)) { ?>
                                                <p><a class="retailer-logo" href="<?php get_the_permalink($key); ?>"><?php echo get_the_post_thumbnail($key, 'retailer-logo-thumb'); ?></a></p>
                                                <?php } ?>
                                                <p>
                                                    <?php
                                                        $location = get_field('address', $key);
                                                        if(isset($location['address'])) {
                                                            $address = substr($location['address'], 0, strrpos($location['address'], ','));
                                                            echo $address;
                                                        }
                                                    ?>
                                                    <br />
                                                    <?php echo get_post_meta( $key, 'city', true ); ?>
                                                    , 
                                                    <?php echo get_post_meta( $key, 'state', true ); ?>
                                                    <br />
                                                    <?php echo get_post_meta( $key, 'country', true ); ?>
                                                    , 
                                                    <?php echo get_post_meta( $key, 'postcode', true ); ?>
                                                </p>
                                                <p><?php get_field('phone_number', $key); ?></p>
                                                <p><a class="custom_button" href="<?php get_the_permalink($key); ?>/#contact">Contact Store</a><a class="custom_button" href="<?php get_the_permalink($key); ?>/#direction">Get Direction</a></p>
                                            </div>
                                        </div>
                        <?php } 
                        wp_reset_postdata();
                        if($i == 0) {

                        ?>
                            <p class="errorMsg">No Store found near this address...</p>
                        <?php  
                        }
                    } else {
                        if(!isset($_GET['country'])) {
                            /*$args = array(
                                'post_type' => 'retailer',
                                'post_status' => 'publish',
                                'posts_per_page' => -1,
                                'orderby' => 'title',
                                'order' => 'ASC'
                            );
                            $the_query = new WP_Query( $args );
                            if ( $the_query->have_posts() ) :
                            $i = 0;
                            /*
                            while ( $the_query->have_posts() ) : $the_query->the_post();
                                $radius = 75;
                                $radius = $radius * 1609.34;
                                $ip = $_SERVER['REMOTE_ADDR'];
                                $details = json_decode(processURL("http://ipinfo.io/{$ip}/json"));
                                $origin = $details->loc;
                                $location = get_field('address');
                                if(isset($location['address'])) {
                                    $address = substr($location['address'], 0, strrpos($location['address'], ','));
                                }
                                $destination = $address;
                                $data = processURL('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.urldecode($origin).'&destinations='.$location['lat'].','.$location['lng'].'&mode=driving&language=en-EN&units=imperial&sensor=false&key=AIzaSyBFhNGeLMvFXt6nyjbh_FazL-yB5OnFGug');
                                $result = json_decode($data, true);
                                foreach($result['rows'] as $distance) {
                                	$dis = $distance['elements'][0]['distance']['value'];
                                    $status = $distance['elements'][0]['status'];
                                    $d = $dis;
                                    if($status != 'ZERO_RESULTS') {
                                        if($d <= $radius) {
                                        ?>
                                            <div class="col span_1_of_3">
                                                <div class="single_store matchheight">
                                                    <h3><?php the_title(); ?></h3>
                                                    <p><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('retailer-logo-thumb'); ?></a></p>
                                                    <p>
                                                        <?php
                                                            $location = get_field('address');
                                                            if(isset($location['address'])) {
                                                                $address = substr($location['address'], 0, strrpos($location['address'], ','));
                                                                echo $address;
                                                            }
                                                        ?>
                                                        <br />
                                                        <?php echo get_post_meta( get_the_ID(), 'city', true ); ?>
                                                        , 
                                                        <?php echo get_post_meta( get_the_ID(), 'state', true ); ?>
                                                        <br />
                                                        <?php echo get_post_meta( get_the_ID(), 'country', true ); ?>
                                                        , 
                                                        <?php echo get_post_meta( get_the_ID(), 'postcode', true ); ?>
                                                    </p>
                                                    <p><?php the_field('phone_number'); ?></p>
                                                    <p><a class="custom_button" href="<?php the_permalink(); ?>/#contact">Contact Store</a><a class="custom_button" href="<?php the_permalink(); ?>/#direction">Get Direction</a></p>
                                                </div>
                                            </div>                                    
                                        <?php
                                        $i++;
                                        }
                                    }
                                }
                            endwhile;
                            *
                            while ( $the_query->have_posts() ) : $the_query->the_post();
                                ?>
                                    <div class="col span_1_of_3">
                                        <div class="single_store matchheight">
                                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                            <?php if(has_post_thumbnail()) { ?>
                                            <p><a class="retailer-logo" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('retailer-logo-thumb'); ?></a></p>
                                            <?php } ?>
                                            <p>
                                                <?php
                                                    $location = get_field('address');
                                                    if(isset($location['address'])) {
                                                        $address = substr($location['address'], 0, strpos($location['address'], ','));
                                                        echo $address;
                                                    }
                                                ?>
                                                <br />
                                                <?php echo get_post_meta( get_the_ID(), 'city', true ); ?>
                                                , 
                                                <?php echo get_post_meta( get_the_ID(), 'state', true ); ?>
                                                <br />
                                                <?php echo get_post_meta( get_the_ID(), 'country', true ); ?>
                                                , 
                                                <?php echo get_post_meta( get_the_ID(), 'postcode', true ); ?>
                                            </p>
                                            <p><?php the_field('phone_number'); ?></p>
                                            <p><a class="custom_button" href="<?php the_permalink(); ?>/#contact">Contact Store</a><a class="custom_button" href="<?php the_permalink(); ?>/#direction">Get Direction</a></p>
                                        </div>
                                    </div>                                    
                                <?php
                            $i++;
                            endwhile;
                            endif;
                            wp_reset_postdata();
                            if($i == 0) {
                            ?>
                                <p class="errorMsg">No Store found near your address...</p>
                            <?php  
                            }*/
                        } else {
                            $country_data = $_GET['country'];
                            $args = array(
                                'post_type' => 'retailer',
                                'post_status' => 'publish',
                                'posts_per_page' => -1,
                                'orderby' => 'title',
                                'order' => 'ASC',
                                'meta_key' => 'country',
                                'meta_value' => $country_data,
                                'meta_compare' => 'LIKE'
                            );
                            $the_query = new WP_Query( $args );
                            if ( $the_query->have_posts() ) {
                            $i = 0;
                            while ( $the_query->have_posts() ) : $the_query->the_post();
                            $location = get_field('address');
                            if(isset($location['address'])) {
                                $address = substr($location['address'], 0, strrpos($location['address'], ','));
                            }
                            ?>
                                <div class="col span_1_of_3">
                                    <div class="single_store matchheight">
                                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <?php if(has_post_thumbnail()) { ?>
                                            <p><a class="retailer-logo" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('retailer-logo-thumb'); ?></a></p>
                                        <?php } ?>
                                        <p>
                                            <?php
                                                $location = get_field('address');
                                                if(isset($location['address'])) {
                                                    $address = substr($location['address'], 0, strrpos($location['address'], ','));
                                                    echo $address;
                                                }
                                            ?>
                                            <br />
                                            <?php echo get_post_meta( get_the_ID(), 'city', true ); ?>
                                            , 
                                            <?php echo get_post_meta( get_the_ID(), 'state', true ); ?>
                                            <br />
                                            <?php echo get_post_meta( get_the_ID(), 'country', true ); ?>
                                            , 
                                            <?php echo get_post_meta( get_the_ID(), 'postcode', true ); ?>
                                        </p>
                                        <p><?php the_field('phone_number'); ?></p>
                                        <p><a class="custom_button" href="<?php the_permalink(); ?>/#contact">Contact Store</a><a class="custom_button" href="<?php the_permalink(); ?>/#direction">Get Direction</a></p>
                                    </div>
                                </div>
                            <?php
                            endwhile;
                            } else {
                            ?>
                                <p class="errorMsg">No Store found in this Country...</p>
                            <?php   
                            }
                            wp_reset_postdata();
                        }
                    }
                ?>
                </div>
                </div>                        
	        </div>
	    </div>
	</div>
</div>
<?php get_footer(); ?>