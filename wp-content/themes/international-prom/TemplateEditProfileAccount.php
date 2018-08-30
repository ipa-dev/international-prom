<?php /* Template Name: Edit Profile */ ?>
<?php
// Report all errors
error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);

require_once 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
( new \Dotenv\Dotenv( __DIR__ . '/' ) )->load();
?>
<?php if ( is_user_logged_in() ) { ?>
	<?php get_header(); ?>
	<?php
	global $user_ID;
	global $wpdb;
	?>
	<?php
	$role = get_user_role( $user_ID );
	if ( ! session_id() ) {
		session_start();
	}
	?>
<div id="content">
	<div class="maincontent">
		<div class="section group">
			<div class="col span_12_of_12">
				<div class="container">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
					<h1><span><?php the_title(); ?></span></h1>
					<div><?php //the_content(); ?></div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="my_account">
	<div class="maincontent">
		<div class="section group">
			<div class="col span_12_of_12">
	<?php if ( $role == 'shopper' ) { ?>

				<?php
				if ( isset( $_POST['register'] ) ) {
						global $wpdb;
					if ( ! empty( $_POST['pw1'] ) ) {
						$new_user_id = wp_update_user(
							array(
								'ID'            => $user_ID,
								'user_login'    => $_POST['email_id'],
								'user_pass'     => $_POST['pw1'],
								'user_email'    => $_POST['email_id'],
								'first_name'    => $_POST['fname'],
								'last_name'     => $_POST['lname'],
								'user_nicename' => $_POST['email_id'],
							)
						);
					} else {
						$new_user_id = wp_update_user(
							array(
								'ID'            => $user_ID,
								'user_login'    => $_POST['email_id'],
								'user_email'    => $_POST['email_id'],
								'first_name'    => $_POST['fname'],
								'last_name'     => $_POST['lname'],
								'user_nicename' => $_POST['email_id'],
							)
						);
					}




						update_post_meta( $page->ID, 'city', sanitize_text_field( $_POST['city'] ) );
						update_post_meta( $page->ID, 'phone', sanitize_text_field( $_POST['phone'] ) );
						update_post_meta( $page->ID, 'website', sanitize_text_field( $_POST['website'] ) );
						update_post_meta( $page->ID, 'country', sanitize_text_field( $_POST['country'] ) );
						update_post_meta( $page->ID, 'state', sanitize_text_field( $_POST['state'] ) );
						update_post_meta( $page->ID, 'postcode', sanitize_text_field( $_POST['zip'] ) );

						$address                  = $_POST['address'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', United States';
						$urlAdress                = urlencode( $address );
						$url                      = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $urlAddress;
						$data                     = processURL( $url );
						$data                     = json_decode( $data );
						$lat                      = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
						$long                     = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
						$address_array            = array();
						$address_array['address'] = $address;
						$address_array['lat']     = $lat;
						$address_array['lng']     = $long;
						$new_address              = serialize( $address_array );

						update_post_meta( $page->ID, 'address', $new_address );

						update_user_meta( $new_user_id, 'address', sanitize_text_field( $_POST['address'] ) );
						//update_user_meta( $new_user_id, 'store_name', sanitize_text_field( $_POST['store_name'] ) );
						update_user_meta( $new_user_id, 'website', sanitize_text_field( $_POST['website'] ) );
						update_user_meta( $new_user_id, 'phone', sanitize_text_field( $_POST['phone'] ) );
						update_user_meta( $new_user_id, 'country', sanitize_text_field( $_POST['country'] ) );
						update_user_meta( $new_user_id, 'city', sanitize_text_field( $_POST['city'] ) );
						update_user_meta( $new_user_id, 'state', sanitize_text_field( $_POST['state'] ) );
						update_user_meta( $new_user_id, 'postcode', sanitize_text_field( $_POST['zip'] ) );
						update_user_meta( $new_user_id, 'store_des', sanitize_text_field( $_POST['store_des'] ) );
						$args      = array(
							'post_type'      => 'retailer',
							'posts_per_page' => -1,
							'post_status'    => 'publish',
							'author'         => $user_ID,
						);
						$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) :
						while ( $the_query->have_posts() ) :
							$the_query->the_post();
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
				<?php $user_info = get_userdata( $user_ID ); ?>
				<form id="joinus" class="registration" action="" method="post" enctype="multipart/form-data">

					<?php
						//Import Base Fields for Account
						require_once("phpmodule_includes/EditProfileAccount/ModuleBaseEditAccountForm.php");
					?>

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
	<?php if ( $role == 'retailer') { ?>
					<?php
					if ( isset( $_POST['register'] ) ) {
							global $wpdb;
						if ( ! empty( $_POST['pw1'] ) ) {
							$new_user_id = wp_update_user(
								array(
									'ID'            => $user_ID,
									'user_login'    => $_POST['email_id'],
									'user_pass'     => $_POST['pw1'],
									'user_email'    => $_POST['email_id'],
									'first_name'    => $_POST['fname'],
									'last_name'     => $_POST['lname'],
									'user_nicename' => $_POST['email_id'],
								)
							);
						} else {
							$new_user_id = wp_update_user(
								array(
									'ID'            => $user_ID,
									'user_login'    => $_POST['email_id'],
									'user_email'    => $_POST['email_id'],
									'first_name'    => $_POST['fname'],
									'last_name'     => $_POST['lname'],
									'user_nicename' => $_POST['email_id'],
								)
							);
						}

							update_user_meta( $new_user_id, 'store_name', sanitize_text_field( $_POST['store_name'] ) );
							  update_user_meta( $new_user_id, 'store_des', sanitize_text_field( $_POST['store_des'] ) );
							update_user_meta( $new_user_id, 'store_address', sanitize_text_field( $_POST['store_address'] ) );
							update_user_meta( $new_user_id, 'store_city', sanitize_text_field( $_POST['store_city'] ) );
							update_user_meta( $new_user_id, 'store_state', sanitize_text_field( $_POST['store_state'] ) );
							update_user_meta( $new_user_id, 'store_zip', sanitize_text_field( $_POST['store_zip'] ) );

							$args      = array(
								'post_type'      => 'retailer',
								'posts_per_page' => -1,
								'post_status'    => 'publish',
								'author'         => $user_ID,
							);
							$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) :
							while ( $the_query->have_posts() ) :
								$the_query->the_post();
								update_post_meta( get_the_ID(), 'author_email', $_POST['email_id'] );
							endwhile;
							endif;
							wp_reset_postdata();
							//header("Location: ".get_bloginfo('home')."/my-account/");
						?>
								<p class="successMsg">Profile Updated</p>
							<?php

							update_user_meta( $new_user_id, 'user_timezone', $_POST['select_timezone'] );

							if ( isset( $_POST['istilist_email'] ) && isset( $_POST['istilist_password'] ) ) {
								update_user_meta( $new_user_id, 'istilist_email', $_POST['istilist_email'] );
								update_user_meta( $new_user_id, 'istilist_password', $_POST['istilist_password'] );
							}
							if ( isset( $_POST['page_options'] ) ) {
								$active_page_temp = get_user_meta( $new_user_id, 'active_page', true );
								if ( empty( $active_page_temp ) ) {
									add_user_meta( $new_user_id, 'active_page', $_POST['page_options'] );
								} else {
									update_user_meta( $new_user_id, 'active_page', $_POST['page_options'] );
								}
							}
					}
					?>
					<?php $user_info = get_userdata( $user_ID ); ?>
				<form id="joinus" class="registration" action="" method="post" enctype="multipart/form-data">

					<?php
						//Import Base Fields for Account
						require_once("phpmodule_includes/EditProfileAccount/ModuleBaseEditAccountForm.php");
						require_once("phpmodule_includes/EditProfileAccount/ModuleRetailerEditAccountForm.php");
					?>




					<div class="section group">
						<div class="col span_12_of_12">
							<label for="select_timezone">Select Timezone</label>
							<select required="required" id="select_timezone" name="select_timezone">
								<?php
								$user_timezone        = get_user_meta( $user_ID, 'user_timezone', true );
								$timezone_identifiers = DateTimeZone::listIdentifiers( DateTimeZone::ALL );
								foreach ( $timezone_identifiers as $tz ) {
									if ( $tz == $user_timezone ) {
										echo "<option selected value='" . $tz . "'>" . $tz . '</option>';
									} else {
										echo "<option value='" . $tz . "'>" . $tz . '</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
					<?php require_once("phpmodule_includes/EditProfileAccount/ModuleSocialConnect.php"); ?>
					<div class="section group">
						<div class="col span_12_of_12">
							<input type="submit" name="register" value="Update" class="submit-button" />
						</div>
					</div>
				</form>


	<?php } ?>
</div>
</div>
</div>
</div>
	<?php get_footer(); ?>
<?php } else {
	header( 'Location: ' . get_bloginfo( 'home' ) . '/login' );
} ?>
