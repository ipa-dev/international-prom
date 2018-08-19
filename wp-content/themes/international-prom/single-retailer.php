<?php get_header(); ?>
<div id="content">
	<div class="maincontent">
		<div class="section group">
			<div class="col span_4_of_12">
				<div id="direction" class="store_contact">
					<h1>Contact Store </h1>
					<?php echo do_shortcode( '[contact-form-7 id="225" title="Store Contact form"]' ); ?>
				</div>
			</div>
			<div class="col span_8_of_12"> 
				<div class="container_store">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
					<h1><?php the_title(); ?></h1>
					<h2>Address</h2>
					<p>
						<?php
							$location = get_field( 'address' );
						if ( isset( $location['address'] ) ) {
							$address = substr( $location['address'], 0, strpos( $location['address'], ',' ) );
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
					<p><?php the_field( 'phone_number' ); ?></p>
					<p><a href="http://<?php echo get_post_meta( get_the_ID(), 'website', true ); ?>" target="_blank"><?php echo get_post_meta( get_the_ID(), 'website', true ); ?></a></p>
					<div><?php the_content(); ?></div>
					<div id="events">
						<?php
						//$author_id = get_the_author_ID();
						$this_retailer = get_the_ID();
						$args          = array(
							'post_type'      => 'event',
							'posts_per_page' => -1,
							'post_status'    => 'publish',
						);
						$the_query     = new WP_Query( $args );
						if ( $the_query->have_posts() ) :
							?>
						<h2>ViP Events</h2>
						<div class="events_list">
							<?php
							while ( $the_query->have_posts() ) :
								$the_query->the_post();
								?>
								<?php $event_retailer = get_field( 'assign_to_retailer' ); ?>
								<?php if ( $event_retailer->ID == $this_retailer ) { ?>
							<div class="event">
							   <div class="section group">
									<div class="col span_3_of_12"> 
										<?php
											/*
											$format_in = 'm-d-Y';
											$format_out = 'F j, Y';
											$date = DateTime::createFromFormat($format_in, get_field('date'));
											*/
										?>
										<div class="date"><strong>Date: </strong><?php echo the_field( 'date' );//echo $date->format( $format_out ); ?></div>
									</div>
									<div class="col span_3_of_12">
										<?php
											/*
											$format_in = 'H:i';
											$format_out = 'h:i a';
											$time = DateTime::createFromFormat($format_in, get_field('time'));
											*/
										?>
										<div class="date"><strong>Time: </strong><?php echo the_field( 'time' );//echo $time->format( $format_out ); ?></div>                        	        </div>
									<div class="col span_4_of_12">  
										<div class="event_details">
											<p>
												<strong><?php the_title(); ?></strong>
												<br />
												<?php the_excerpt_max_charlength_by_content( 70, get_post_meta( get_the_ID(), 'where', true ) ); ?>
											</p>
										</div>                        
									</div>
									<div class="col span_2_of_12">
										<div class="event_buy"><a href="<?php the_permalink(); ?>">View Details</a></div>
									</div>
								</div>
							</div>
							<?php } ?>
							<?php endwhile; ?>
						</div>
							<?php
							endif;
							wp_reset_postdata();
						?>
					</div>
					<?php endwhile; ?>
					<div id="direction">
						<h2>Location</h2>
						<p id="map">
							<iframe width="100%" height="215" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.ca/maps?center=<?php the_field( 'address' ); ?>&q=<?php the_field( 'address' ); ?>&zoom=14&size=300x300&output=embed&iwloc=near"></iframe>
						</p>
					</div>  
				</div>                         
			</div>
		</div>
	</div>
</div>
<script>
jQuery(document).ready(function() {
	getLocation();
})
var x = document.getElementById("map");

function getLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition);
	} else { 
		x.innerHTML = "Geolocation is not supported by this browser.";
	}
}

function showPosition(position) {
	x.innerHTML = "Latitude: " + position.coords.latitude + 
	"<br>Longitude: " + position.coords.longitude;
	x.innerHTML = '<iframe id="iframe-google-map" class="content" width="100%" height="215" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"  src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyBFhNGeLMvFXt6nyjbh_FazL-yB5OnFGug&origin=' + position.coords.latitude + '%2C' + position.coords.longitude + '&destination=<?php the_field( 'address' ); ?>&avoid=tolls|highways" width="600" height="450" frameborder="0" style="border:0">';
}
</script>
<?php get_footer(); ?>
