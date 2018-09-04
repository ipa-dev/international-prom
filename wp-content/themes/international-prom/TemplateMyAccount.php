<?php /* Template Name: My Account */ ?>
<?php if ( is_user_logged_in() ) { ?>
	<?php get_header(); ?>
	<?php
	global $user_ID;
	global $wpdb;
	?>
	<?php $user_info = get_userdata( $user_ID ); ?>
	<?php $role = get_user_role( $user_ID ); ?>
<div id="content">
	<div class="maincontent">
		<div class="section group">
			<div class="col span_12_of_12">
				<div class="container">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
					<h1><span>
						<?php
						if ( $role == 'retailer' ) {
							?>
						Retailer Account Information
							<?php
						} else {
							?>
						Shopper Account Information<?php } ?></span></h1>
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
			<div class="col span_12_of_12">
				<div class="acc_sec">
				<?php
				$store_name    = get_user_meta( $user_ID, 'store_name', true );
					  $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM internationalprom_posts WHERE post_type='retailer' and post_title=%s", $store_name ) );
				for ( $i = 0;$i < count( $results );$i++ ) {
					?>

				<div class="section group">
				  <div class="location">
					<?php $user_info = get_userdata( $user_ID ); ?>

				   <div class="col span_6_of_12">
					<?php
					if ( $role == 'retailer' ) {
						?>
						<p><strong>Store Name: </strong><?php echo get_user_meta( $user_ID, 'store_name', true ); ?></p><?php } ?>
						<p><strong>First Name: </strong><?php echo $user_info->first_name; ?></p>
						<p><strong>Last Name: </strong><?php echo $user_info->last_name; ?></p>
						<p><strong>Email: </strong><?php echo $user_info->user_email; ?></p>
					<?php
					if ( $role == 'retailer' ) {
						?>
						<p><strong>Phone: </strong><?php echo get_post_meta( $results[ $i ]->ID, 'phone_number', true ); ?></p><?php } ?>
						<p><strong>City: </strong><?php echo get_post_meta( $results[ $i ]->ID, 'city', true ); ?></p>
				   </div>
				   <div class="col span_6_of_12">
						<p><strong>State: </strong><?php echo get_post_meta( $results[ $i ]->ID, 'state', true ); ?></p>
						<p><strong>Zip: </strong><?php echo get_post_meta( $results[ $i ]->ID, 'postcode', true ); ?></p>
						<p><strong>Country: </strong>
					  <?php echo get_post_meta( $results[ $i ]->ID, 'country', true ); ?>
						</p>
					<?php
					if ( $role == 'retailer' ) {
						?>
						<p><strong>Website: </strong><a href="http://<?php echo get_post_meta( $results[ $i ]->ID, 'website', true ); ?>" target="_blank"><?php echo get_post_meta( $results[ $i ]->ID, 'website', true ); ?></a></p><?php } ?>
					<?php
					if ( $role == 'retailer' ) {
						?>
						<p><strong>Store Description: </strong><?php echo get_post_meta( $results[ $i ]->ID, 'store_des', true ); ?></p><?php } ?>
						<p></p>
						<p></p>
				   </div>
				   </div>
				</div>
					<?php if ( $role == 'retailer' ) { ?>
				<div class="location_buttons">
					 <form action="<?php bloginfo( 'url' ); ?>/edit-profile" method="post">
					  <input type="hidden" name="post_id" value="<?php echo $results[ $i ]->ID; ?>" />
					  <button class="custom_button">Edit Location</button>
					 </form>
					 <form action="<?php bloginfo( 'url' ); ?>/add-vip-event" method="post">
					  <input type="hidden" name="post_id" value="<?php echo $results[ $i ]->ID; ?>" />
					  <button class="custom_button">Add ViP Event</button>
					 </form>
				</div>
				<?php } ?>
				<?php } ?>
				<!--<div class="section group">
					<?php /*$user_info = get_userdata($user_ID); */ ?>
					<div class="col span_6_of_12">
						<?php /*if($role == 'retailer') { */ ?><p><strong>Store Name: </strong><?php /*echo get_user_meta( $user_ID, 'store_name', true); */ ?></p><?php /*} */ ?>
						<p><strong>First Name: </strong><?php /*echo $user_info->first_name; */ ?></p>
						<p><strong>Last Name: </strong><?php /*echo $user_info->last_name; */ ?></p>
						<p><strong>Email: </strong><?php /*echo $user_info->user_email; */ ?></p>
						<?php /*if($role == 'retailer') { */ ?><p><strong>Phone: </strong><?php /*echo get_post_meta( $results[$i]->ID, 'phone_number', true); */ ?></p><?php /*} */ ?>
						<p><strong>City: </strong><?php /*echo get_user_meta( $user_ID, 'city', true); */ ?></p>
					</div>
					<div class="col span_6_of_12">
						<p><strong>State: </strong><?php /*echo get_post_meta( $results[$i]->ID, 'state', true); */ ?></p>
						<p><strong>Zip: </strong><?php /*echo get_post_meta( $results[$i]->ID, 'postcode', true); */ ?></p>
						<p><strong>Country: </strong>
						<?php
						/*
							$url = TEMPLATEPATH.'/countries.xml';
							$xml = simplexml_load_file($url);
							//print_r($xml);
							foreach($xml->country as $country) {
								if($country['code'] == get_user_meta( $user_ID, 'country', true)) {
									echo $country;
								}
							}
						*/
						?>
						</p>
						<?php /*if($role == 'retailer') { */ ?><p><strong>Website: </strong><a href="<?php /*echo get_user_meta( $user_ID, 'website', true); */ ?>" target="_blank"><?php /*echo get_user_meta( $user_ID, 'website', true); */ ?></a></p><?php /*} */ ?>
						<?php /*if($role == 'retailer') { */ ?><p><strong>Store Description: </strong><?php /*echo get_user_meta( $user_ID, 'store_des', true); */ ?></p><?php /*} */ ?>
					</div>
				</div>-->
				<a class="custom_button" href="<?php bloginfo( 'url' ); ?>/edit-profile/">Edit Profile</a>
				<?php
				if ( $role == 'retailer' ) {
					?>
					<a class="custom_button" href="<?php bloginfo( 'url' ); ?>/marketing-manager/">Marketing Manager</a>
					<?php
					$args           = array(
						'post_type'      => 'retailer',
						'posts_per_page' => -1,
						'post_status'    => array(
							'publish',
							'pending',
						),
						'author'         => $user_ID,
					);
					$retailer_query = new WP_Query( $args );

					?>
					<?php if ( $retailer_query->found_posts >= 5 ) { ?>
				<a class="custom_button" href="javascript:void(0);">Can not add more than 5 Locations</a>
				<?php } else { ?>
				<!--<a class="custom_button" href="<?php bloginfo( 'url' ); ?>/store-add/">Add Location</a>-->
				<?php } ?>
				<!--<a class="custom_button" href="add-vip-event">Add ViP Event</a>-->
					<?php
				}
				?>
				</div>


			</div>
		</div>
	</div>
</div>
	<?php get_footer(); ?>
<?php } else {
	header( 'Location: ' . get_bloginfo( 'home' ) . '/sign-in' );
} ?>
