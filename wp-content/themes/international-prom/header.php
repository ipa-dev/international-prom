<?php ob_start(); ?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<!-- Responsive and mobile friendly stuff -->
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
<?php
/*
* Print the <title> tag based on what is being viewed.
*/
global $page, $paged;

wp_title( '', true, 'right' );

// Add the blog name.
if ( is_home() || is_front_page() ) {
	bloginfo( 'name' );
}

// Add the blog description for the home/front page.
$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) ) {
	echo " | $site_description";
}

// Add a page number if necessary:
/*if ($paged >= 2 || $page >= 2)
	echo ' | ' . sprintf(__('Page %s', 'twentyeleven'), max($paged, $page));
*/
?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<!-- Responsive Stylesheets -->
<link rel="stylesheet" media="all" href="<?php bloginfo( 'template_directory' ); ?>/css/commoncssloader.css" />

<link rel="stylesheet" media="only screen and (max-width: 1024px) and (min-width: 769px)" href="<?php bloginfo( 'template_directory' ); ?>/css/1024.css">
<link rel="stylesheet" media="only screen and (max-width: 768px) and (min-width: 481px)" href="<?php bloginfo( 'template_directory' ); ?>/css/768.css">
<link rel="stylesheet" media="only screen and (max-width: 480px)" href="<?php bloginfo( 'template_directory' ); ?>/css/480.css">
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>?ver=<?php echo( mt_rand( 10, 100 ) ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<!-- Custom Responsive Stylesheets -->
<link rel="stylesheet" media="only screen and (max-width: 1024px) and (min-width: 993px)" href="<?php bloginfo( 'template_directory' ); ?>/css/mediaquerycss/styleMax1024.css?ver=<?php echo( mt_rand( 10, 100 ) ); ?>">
<link rel="stylesheet" media="only screen and (max-width: 992px) and (min-width: 769px)" href="<?php bloginfo( 'template_directory' ); ?>/css/mediaquerycss/styleMax992.css?ver=<?php echo( mt_rand( 10, 100 ) ); ?>">
<link rel="stylesheet" media="only screen and (max-width: 768px) and (min-width: 481px)" href="<?php bloginfo( 'template_directory' ); ?>/css/mediaquerycss/styleMax768.css?ver=<?php echo( mt_rand( 10, 100 ) ); ?>">
<link rel="stylesheet" media="only screen and (max-width: 480px)" href="<?php bloginfo( 'template_directory' ); ?>/css/mediaquerycss/styleMax480.css?ver=<?php echo( mt_rand( 10, 100 ) ); ?>">




<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_directory' ); ?>/css/jquery.fancybox.css" />
<script src="<?php bloginfo( 'template_directory' ); ?>/js/jquery.fancybox.pack.js"></script>

<script src="<?php bloginfo( 'template_directory' ); ?>/js/modernizr-2.8.2-min.js"></script>
<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/css/slicknav.css" />
<script src="<?php bloginfo( 'template_directory' ); ?>/js/jquery.slicknav.js"></script>

<script>
	jQuery(function(){
		jQuery('.nav').slicknav({
		  prependTo:'#rspnavigation',
		  label:''
		});
	});
</script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery(".fancybox").fancybox({

		});
		jQuery(".fancybox_video").fancybox({
		  helpers: {
			  title : {
				  type : 'float'
			  }
		  },
		  openEffect	: 'none',
		  closeEffect	: 'none'
		});
		jQuery(".fancybox_login_popup").fancybox({
			maxWidth: 700,
			maxHeight: 300,
			autoSize: false
		});
		jQuery(".gallery_img").fancybox({
		  helpers: {
			  title : {
				  type : 'float'
			  }
		  },
		  openEffect	: 'none',
		  closeEffect	: 'none'
		});
		jQuery(".fancybox_gallery").fancybox({
			maxWidth: 600,
			maxHeight: 280,
			autoSize: false
		});
		jQuery(".product_img").fancybox({
			helpers: {
			  title : {
				  type : 'float'
			  },
			},
			maxWidth: 500,
			autoWidth: false
		});
	});
	jQuery(document).ready(function() {
		jQuery('#link_id').trigger('click');
	});
</script>

	<script src="https://use.fontawesome.com/83825ee098.js"></script>

<script src="<?php bloginfo( 'template_directory' ); ?>/js/jquery.matchHeight-min.js"></script>

<script type="text/javascript">
jQuery(function($){
	$('.matchheight').matchHeight();
});
</script>


<script>
function validateEmail(p1, p2) {
	if (p1.value != p2.value || p1.value == '' || p2.value == '') {
		p2.setCustomValidity('Email does not match');
	} else {
		p2.setCustomValidity('');
	}
}
</script>

<script>
function validatePass(p1, p2) {
	if (p1.value != p2.value || p1.value == '' || p2.value == '') {
		p2.setCustomValidity('Password does not match');
	} else {
		p2.setCustomValidity('');
	}
}
</script>

<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/css/jquery.bxslider.css" />


<script src="<?php bloginfo( 'template_directory' ); ?>/js/jquery.bxslider.min.js"></script>

<script>
jQuery(document).ready(function(){
  jQuery('.bxslider').bxSlider({
  });
  var pro_slider = jQuery('.pro_slider').bxSlider({
	slideWidth: 185,
	minSlides: 2,
	maxSlides: 6,
	slideMargin: 15,
	pager: false,
	controls: false,
	onSliderLoad: function(){
		jQuery(".pro_slider").css("visibility", "visible");
	  }
  });
  jQuery('.pro_slider_next a').click(function(){
	  pro_slider.goToNextSlide();
	  return false;
	});
  jQuery('.pro_slider_prev a').click(function(){
	  pro_slider.goToPrevSlide();
	  return false;
	});

});
</script>

<script type="text/javascript">
	jQuery(function() {
	jQuery(".nav #mega-menu-wrap-mainmenu li.mega-menu-item-has-children").children("a").attr('href', "javascript:void(0)");
	});
</script>
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css' />

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyBFhNGeLMvFXt6nyjbh_FazL-yB5OnFGug"></script>

<?php
/*wp_register_script("jquery.placepicker.js", "//internationalprom.com/wp-content/themes/internationalprom-theme/js/jquery.placepicker.js");
wp_enqueue_script("jquery.placepicker.js");*/
?>
<script src="<?php bloginfo( 'template_directory' ); ?>/js/jquery.placepicker.js"></script>

<script>

  jQuery(document).ready(function() {

	// Basic usage
	jQuery(".placepicker").placepicker();

	// Advanced usage
	jQuery("#advanced-placepicker").each(function() {
	  var target = this;
	  var collapse = jQuery(this).parents('.form-group').next('.collapse');
	  var map = collapse.find('.another-map-class');

	  var placepicker = jQuery(this).placepicker({
		map: map.get(0),
		placeChanged: function(place) {
		  console.log("place changed: ", place.formatted_address, this.getLocation());
		}
	  }).data('placepicker');
	});

  }); // END document.ready

</script>


<!-- Image Magnifier -->
<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_directory' ); ?>/css/jquery.simpleLens.css" />
<script type="text/javascript" src="<?php bloginfo( 'template_directory' ); ?>/js/jquery.simpleLens.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_directory' ); ?>/css/jquery.simpleGallery.css" />
<script type="text/javascript" src="<?php bloginfo( 'template_directory' ); ?>/js/jquery.simpleGallery.min.js"></script>


<?php if ( is_singular() ) { ?>
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' ); ?>
<meta property="og:url" content="<?php echo get_the_permalink( get_the_ID() ); ?>"/>
<meta property="og:title" content="<?php echo get_the_title( get_the_ID() ); ?>"/>
<meta property="og:content" content="<?php echo get_the_excerpt(); ?>"/>
<meta property="og:image" content="<?php echo $thumb[0]; ?>"/>
<?php endwhile; ?>
<?php } ?>
<meta name="google-site-verification" content="uTJObWWiu2PljyfO9DWp57UsSiQdQ70r-1yQ2EGEblo" />
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-80612038-1', 'auto');
  ga('send', 'pageview');

</script>
<?php
/* We add some JavaScript to pages with the comment form
* to support sites with threaded comments (when in use).
*/
/*(if (is_singular() && get_option('thread_comments'))
	wp_enqueue_script('comment-reply');*/

/* Always have wp_head() just before the closing </head>
* tag of your theme, or you will break many plugins, which
* generally use this hook to add elements to <head> such
* as styles, scripts, and meta tags.
*/
//wp_enqueue_script('jquery');
wp_head();
?>
</head>
<body <?php body_class(); ?>>
<?php if ( ! is_page( 'sign-in' ) ) { ?>
<div id="login-popup" style="display: none;">
<div class="section group">
	<div class="col span_6_of_12">
		<h2>Sign In</h2>
		<?php
		if ( isset( $_POST['logina'] ) ) {
			global $wpdb;
			$username    = $wpdb->escape( $_POST['email_id'] );
			$pwd         = $wpdb->escape( $_POST['pwd'] );
			$user_status = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_login = %s", $username ) );
			if ( $user_status[0]->user_status == 2 ) {
				$login_data                  = array();
				$login_data['user_login']    = $username;
				$login_data['user_password'] = $pwd;
				if ( isset( $_POST['rememberme'] ) ) {
					$login_data['remember'] = 'true';
				} else {
					$login_data['remember'] = 'false';
				}
				$login_count = get_locked_counter();
				if ( $login_count < 2 ) {
					$user_verify = wp_signon( $login_data, true );
					if ( is_wp_error( $user_verify ) ) {
						$errorCode = 3;
						update_locked_counter();
					} else {
						header( 'Location: ' . get_bloginfo( 'home' ) . '/my-account' );
						exit();
					}
				} else {
					$errorCode = 4;
				}
			} else {
				$errorCode = 1; // invalid login details
			}
		}
		?>
		<?php if ( $errorCode == 1 ) { ?>
			<div class="errorMsg">You have not activated your account. Please check your email inbox to activate account.</div>
		<?php } ?>
		<?php if ( $errorCode == 2 ) { ?>
			<div class="errorMsg">Verification failed...Please try again.</div>
		<?php } ?>
		<?php if ( $errorCode == 3 ) { ?>
			<div class="errorMsg">Incorrect login details...Please try again.</div>
		<?php } ?>
		<?php if ( $errorCode == 4 ) { ?>
			<div class="errorMsg">You have had your 3 failed attempts at logging in and now are banned for 10 minutes. Try again later!</div>
		<?php } ?>
			<form class="login" action="" method="POST">
				<div class="section group">
					<div class="col span_12_of_12">
						<label for="username"><?php _e( 'Username or email address' ); ?> <span class="required">*</span></label>
						<input type="email" name="email_id" placeholder="Email Address" value="" required="required" />
					</div>
				</div>
				<div class="section group">
					<div class="col span_12_of_12">
						<label for="password"><?php _e( 'Password' ); ?> <span class="required">*</span></label>
						<input type="password" name="pwd" placeholder="Password" value="" required="required" />
					</div>
				</div>
				<div class="section group">
					<div class="col span_12_of_12">
					<?php wp_nonce_field( 'custom-login' ); ?>
						<input type="submit" name="logina" value="Login" />
						<label for="rememberme" class="inline">
						<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me' ); ?>
					</div>
				</div>
				<div class="section group">
					<div class="col span_12_of_12">
						<a href="<?php bloginfo( 'url' ); ?>/forgot-password">Forgot Password?</a>
					</div>
				</div>
			</form>
	</div>
	<div class="col span_6_of_12">
		<h2>Create an Account</h2>
		<a class="custom_button" href="<?php bloginfo( 'url' ); ?>/registration">Register new account</a>
	</div>
</div>
</div>
<?php } ?>
<?php if ( is_home() ) { ?>
	<?php if ( ! isset( $_COOKIE['newsletter'] ) ) { ?>
		<?php setcookie( 'newsletter', 1, time() + ( 86400 * 30 ), '/' ); ?>
<!--<a href="#inline" style="display: none;" id="link_id" class="fancybox">Pop Up</a>
<div id="inline" style="display: none;">
		<?php
		/*$args = array(
			'page_id' => 179,
			'post_status' => 'publish'
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
		?>
		<div class="section group">
			<div class="col span_7_of_12">
				<?php the_content(); ?>
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Newsletter') ) : ?> <?php endif; ?>
			</div>
			<div class="col span_5_of_12">
				<?php the_post_thumbnail('full') ?>
			</div>
		</div>
		<div class="social_nav">
			<ul>
				<li><a href="<?php echo get_option('facebook'); ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
				<li><a href="<?php echo get_option('twitter'); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
				<li><a href="<?php echo get_option('instagram'); ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
				<li><a href="<?php echo get_option('pinterest'); ?>" target="_blank"><i class="fa fa-pinterest"></i></a></li>
				<li><a href="<?php echo get_option('linkedin'); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
				<li><a href="<?php echo get_option('rss'); ?>" target="_blank"><i class="fa fa-rss"></i></a></li>
			</ul>
		</div>
		<?php
		endwhile;
		endif;
		wp_reset_postdata();*/
		?>
</div>-->
	<?php } ?>
<?php } ?>
<div id="gallery_upload" style="display: none;">
	<div id="dragAndDropFiles" class="uploadArea">
		<h1>Drop Images Here to Upload into Gallery</h1>
	</div>
	<form name="demoFiler" id="demoFiler" enctype="multipart/form-data">
		<p style="display: none;"><input type="file" name="multiUpload" id="multiUpload" multiple="multiple" /></p>
		<p><input type="submit" name="submitHandler" id="submitHandler" value="Upload" class="buttonUpload" /></p>
	</form>
	<div class="progressBar">
		<div class="status"></div>
	</div>
</div>
<div id="header">
	<div class="maincontent noPadding">
		<div class="section group">
			<div class="col span_3_of_12">
				<?php
				if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'Logo' ) ) :
					?>
						<?php endif; ?>
			</div>
			<div class="col span_9_of_12">
				<div class="social_nav">
					<ul>
						<li><a href="<?php echo get_option( 'facebook' ); ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
						<li><a href="<?php echo get_option( 'twitter' ); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
						<li><a href="<?php echo get_option( 'instagram' ); ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
						<li><a href="<?php echo get_option( 'pinterest' ); ?>" target="_blank"><i class="fa fa-pinterest"></i></a></li>
						<li><a href="<?php echo get_option( 'linkedin' ); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
						<li><a href="<?php echo get_option( 'rss' ); ?>" target="_blank"><i class="fa fa-rss"></i></a></li>
					</ul>
				</div>
				<div class="nav_acc">
					<?php if ( is_user_logged_in() ) { ?>
						<?php global $user_ID; ?>
						<?php $role = get_user_role( $user_ID ); ?>
						<?php if ( $role == 'retailer' ) { ?>
							<?php wp_nav_menu( array( 'theme_location' => 'accnav' ) ); ?>
						<?php } ?>
						<?php if ( $role == 'customer' ) { ?>
						<ul>
							<li><a href="<?php bloginfo( 'url' ); ?>/favorites/">Favorites</a></li>
							<li style="display: none;"><a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a></li>
						</ul>
						<?php } ?>
					<?php } else { ?>
						<div id="sign-in" style="display: none;">
							<div class="sign_in_box">
								<a href="<?php bloginfo( 'url' ); ?>/sign-in/?role=shopper">
									<img src="<?php bloginfo( 'template_directory' ); ?>/images/shopper.png" />
									<br />
									Shopper Login
								</a>
							</div>
							<div class="sign_in_box">
								<a href="<?php bloginfo( 'url' ); ?>/sign-in/?role=retailer">
									<img src="<?php bloginfo( 'template_directory' ); ?>/images/retailer.png" />
									<br />
									Retailer Login
								</a>
							</div>
						</div>
						<ul>
							<li><a class="fancybox_login_popup" href="#sign-in">Sign In</a></li>
							<li><a class="fancybox_login_popup" href="#sign-in">Follow Our Blog </a></li>
						</ul>
					<?php } ?>
				</div>
				<div class="nav_wrapper">
					<div class="section group">
						<div class="col span_10_of_12">
							<div class="nav"><?php wp_nav_menu( array( 'theme_location' => 'mainmenu' ) ); ?></div>
							<div id="rspnavigation"></div>
						</div>
						<div class="col span_2_of_12">
							<form class="nav_search" method="GET" action="<?php bloginfo( 'url' ); ?>">
								<input type="text" name="s" placeholder="SEARCH" />
								<input type="submit" value="&#xf002;" />
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
