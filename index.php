<?php get_header(); ?>
<div id="banner">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <div class="col span_12_of_12 noMargin">
                <?php echo do_shortcode('[rev_slider home]'); ?>
	        </div>
	    </div>
	</div>
</div>
<div id="blogposts">
	<div class="maincontent">
	    <div class="section group">
            <div class="col span_8_of_12">
                <?php
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 1,
                        'post_status' => 'publish',
                        'meta_key' => 'featured',
                        'meta_value' => 1,
                        'meta_compare' => '=',
                        'paged' => 1
                    );
                    $the_query = new WP_Query( $args );
                    if ( $the_query->have_posts() ) :
                    while ( $the_query->have_posts() ) : $the_query->the_post();
                ?>
                <script>
                jQuery(document).ready(function(){
                  var slider_<?php echo get_the_ID(); ?> = jQuery('.featured_slider-<?php echo get_the_ID(); ?>').bxSlider({
                    slideWidth: 63,
                    minSlides: 1,
                    maxSlides: 3,
                    slideMargin: 50,
                    pager: false,
                    controls: false,
                    onSliderLoad: function(){
                        jQuery(".featured_slider").css("visibility", "visible");
                      }
                  });
                  jQuery('.featured_slider_next-<?php echo get_the_ID(); ?> a').click(function(){
                      slider_<?php echo get_the_ID(); ?>.goToNextSlide();
                      return false;
                    });
                  jQuery('.featured_slider_prev-<?php echo get_the_ID(); ?> a').click(function(){
                      slider_<?php echo get_the_ID(); ?>.goToPrevSlide();
                      return false;
                    });
                })
                </script>
                <div class="single_post">
                    <div class="section group">
        	        <div class="col span_6_of_12">
                        <a class="featured_img" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-big-thumb'); ?></a>
        	        </div>
                    <div class="col span_6_of_12">
                        <ul class="details">
                            <li>BY <?php the_author(); ?></li>
                            <li><?php the_time('F j, Y'); ?></li>
                            <li>IN <?php the_category(', ') ?></li>
                        </ul>
                        <h1><?php the_title(); ?></h1>
                        <p><?php the_excerpt_max_charlength(175); ?></p>
                        <?php
                            $pro_assign = get_post_meta( get_the_ID(), 'pro_assign', true );
                            if(!empty($pro_assign)) {
                                $pro_assign_array = $pro_assign;
                        ?>
                        <?php if(!empty($pro_assign_array)) { ?>
                        <div class="featured">
                            <h2>featured items</h2>
                            <div class="featured_slider_wrapper">
                                <div class="featured_slider_prev featured_slider_prev-<?php echo get_the_ID(); ?>"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_prev.png" /></a></div>
                                <div class="featured_slider_next featured_slider_next-<?php echo get_the_ID(); ?>"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_next.png" /></a></div>
                                <div class="featured_slider-<?php echo get_the_ID(); ?>">
                                    <?php foreach($pro_assign_array as $pro) { ?>
                                    <div><a href="<?php echo get_the_permalink($pro); ?>"><?php echo get_the_post_thumbnail($pro, 'product-small-thumb'); ?></a></div>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <a href="<?php the_permalink(); ?>" class="read_more">Read More</a>
                        <?php get_template_part('social_share'); ?>
                    </div>
                    </div>
                </div>
                <?php
                    endwhile;
                    endif;
                    wp_reset_postdata();
                ?>
                <?php
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 1,
                        'post_status' => 'publish',
                        'meta_key' => 'featured',
                        'meta_value' => 1,
                        'meta_compare' => '=',
                        'paged' => 2
                    );
                    $the_query = new WP_Query( $args );
                    if ( $the_query->have_posts() ) :
                    while ( $the_query->have_posts() ) : $the_query->the_post();
                ?>
                <script>
                jQuery(document).ready(function(){
                  var slider_<?php echo get_the_ID(); ?> = jQuery('.featured_slider-<?php echo get_the_ID(); ?>').bxSlider({
                    slideWidth: 63,
                    minSlides: 1,
                    maxSlides: 3,
                    slideMargin: 50,
                    pager: false,
                    controls: false,
                    onSliderLoad: function(){
                        jQuery(".featured_slider").css("visibility", "visible");
                      }
                  });
                  jQuery('.featured_slider_next-<?php echo get_the_ID(); ?> a').click(function(){
                      slider_<?php echo get_the_ID(); ?>.goToNextSlide();
                      return false;
                    });
                  jQuery('.featured_slider_prev-<?php echo get_the_ID(); ?> a').click(function(){
                      slider_<?php echo get_the_ID(); ?>.goToPrevSlide();
                      return false;
                    });
                })
                </script>
                <div class="single_post">
                    <div class="section group">
                    <div class="col span_6_of_12">
                        <ul class="details">
                            <li>BY <?php the_author(); ?></li>
                            <li><?php the_time('F j, Y'); ?></li>
                            <li>IN <?php the_category(', ') ?></li>
                        </ul>
                        <h1><?php the_title(); ?></h1>
                        <p><?php the_excerpt_max_charlength(175); ?></p>
                        <?php
                            $pro_assign = get_post_meta( get_the_ID(), 'pro_assign', true );
                            if(!empty($pro_assign)) {
                                $pro_assign_array = $pro_assign;
                        ?>
                        <?php if(!empty($pro_assign_array)) { ?>
                        <div class="featured">
                            <h2>featured items</h2>
                            <div class="featured_slider_wrapper">
                                <div class="featured_slider_prev featured_slider_prev-<?php echo get_the_ID(); ?>"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_prev.png" /></a></div>
                                <div class="featured_slider_next featured_slider_next-<?php echo get_the_ID(); ?>"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_next.png" /></a></div>
                                <div class="featured_slider-<?php echo get_the_ID(); ?>">
                                    <?php foreach($pro_assign_array as $pro) { ?>
                                    <div><a href="<?php echo get_the_permalink($pro); ?>"><?php echo get_the_post_thumbnail($pro, 'product-small-thumb'); ?></a></div>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <a href="<?php the_permalink(); ?>" class="read_more">Read More</a>
                        <?php get_template_part('social_share'); ?>
                    </div>
        	        <div class="col span_6_of_12">
                        <a class="featured_img" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-big-thumb'); ?></a>
        	        </div>
                    </div>
                </div>
                <?php
                    endwhile;
                    endif;
                    wp_reset_postdata();
                ?>

	        <?php
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 1,
                        'post_status' => 'publish',
                        'meta_key' => 'featured',
                        'meta_value' => 1,
                        'meta_compare' => '=',
                        'paged' => 3
                    );
                    $the_query = new WP_Query( $args );
                    if ( $the_query->have_posts() ) :
                    while ( $the_query->have_posts() ) : $the_query->the_post();
                ?>
                <script>
                jQuery(document).ready(function(){
                  var slider_<?php echo get_the_ID(); ?> = jQuery('.featured_slider-<?php echo get_the_ID(); ?>').bxSlider({
                    slideWidth: 63,
                    minSlides: 1,
                    maxSlides: 3,
                    slideMargin: 50,
                    pager: false,
                    controls: false,
                    onSliderLoad: function(){
                        jQuery(".featured_slider").css("visibility", "visible");
                      }
                  });
                  jQuery('.featured_slider_next-<?php echo get_the_ID(); ?> a').click(function(){
                      slider_<?php echo get_the_ID(); ?>.goToNextSlide();
                      return false;
                    });
                  jQuery('.featured_slider_prev-<?php echo get_the_ID(); ?> a').click(function(){
                      slider_<?php echo get_the_ID(); ?>.goToPrevSlide();
                      return false;
                    });
                })
                </script>
                <div class="single_post">
                    <div class="section group">
        	        <div class="col span_6_of_12">
                        <a class="featured_img" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-big-thumb'); ?></a>
        	        </div>
                    <div class="col span_6_of_12">
                        <ul class="details">
                            <li>BY <?php the_author(); ?></li>
                            <li><?php the_time('F j, Y'); ?></li>
                            <li>IN <?php the_category(', ') ?></li>
                        </ul>
                        <h1><?php the_title(); ?></h1>
                        <p><?php the_excerpt_max_charlength(175); ?></p>
                        <?php
                            $pro_assign = get_post_meta( get_the_ID(), 'pro_assign', true );
                            if(!empty($pro_assign)) {
                                $pro_assign_array = $pro_assign;
                        ?>
                        <?php if(!empty($pro_assign_array)) { ?>
                        <div class="featured">
                            <h2>featured items</h2>
                            <div class="featured_slider_wrapper">
                                <div class="featured_slider_prev featured_slider_prev-<?php echo get_the_ID(); ?>"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_prev.png" /></a></div>
                                <div class="featured_slider_next featured_slider_next-<?php echo get_the_ID(); ?>"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_next.png" /></a></div>
                                <div class="featured_slider-<?php echo get_the_ID(); ?>">
                                    <?php foreach($pro_assign_array as $pro) { ?>
                                    <div><a href="<?php echo get_the_permalink($pro); ?>"><?php echo get_the_post_thumbnail($pro, 'product-small-thumb'); ?></a></div>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <a href="<?php the_permalink(); ?>" class="read_more">Read More</a>
                        <?php get_template_part('social_share'); ?>
                    </div>
                    </div>
                </div>
                <?php
                    endwhile;
                    endif;
                    wp_reset_postdata();
                ?>
                </div>
            <div class="col span_4_of_12">
                <div class="small_post_wrapper">
                    <?php
                    $designer_cats = apply_filters( 'taxonomy-images-get-terms', '', array(
                        'before'       => '',
                        'after'        => '',
                        'before_image' => '',
                        'after_image'  => '',
                        'image_size'   => 'blog-small-thumb',
                        //'post_id'      => 1234,
                        'taxonomy'     => 'gallery_category',
                    ) );
                    $i=1;
                    foreach ($designer_cats as $designer_cat){
                        if($i<=9){
                    ?>
                    <div class="small_post">
                        <a href="<?php echo esc_url( get_term_link( $designer_cat, $designer_cat->taxonomy ) ); ?>">
                        <?php echo wp_get_attachment_image( $designer_cat->image_id, 'blog-small-thumb' ); ?>
                        <div class="small_post_title_wrapper">
                            <div class="small_post_title">
                                <h1><?php echo $designer_cat->name; ?></h1>
                                <!--<p><?php the_excerpt_max_charlength('30'); ?></p>-->
                            </div>
                        </div>
                        </a>
                    </div>
                    <?php
                    }
                    $i++;
                    }
                    ?>
                </div>
            </div>
	    </div>
	</div>
</div>
<div id="blogposts">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_8_of_12">
                <?php
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 1,
                        'post_status' => 'publish',
                        'meta_key' => 'featured',
                        'meta_value' => 1,
                        'meta_compare' => '=',
                        'paged' => 4
                    );
                    $the_query = new WP_Query( $args );
                    if ( $the_query->have_posts() ) :
                    while ( $the_query->have_posts() ) : $the_query->the_post();
                ?>
                <script>
                jQuery(document).ready(function(){
                  var slider_<?php echo get_the_ID(); ?> = jQuery('.featured_slider-<?php echo get_the_ID(); ?>').bxSlider({
                    slideWidth: 63,
                    minSlides: 1,
                    maxSlides: 3,
                    slideMargin: 50,
                    pager: false,
                    controls: false,
                    onSliderLoad: function(){
                        jQuery(".featured_slider").css("visibility", "visible");
                      }
                  });
                  jQuery('.featured_slider_next-<?php echo get_the_ID(); ?> a').click(function(){
                      slider_<?php echo get_the_ID(); ?>.goToNextSlide();
                      return false;
                    });
                  jQuery('.featured_slider_prev-<?php echo get_the_ID(); ?> a').click(function(){
                      slider_<?php echo get_the_ID(); ?>.goToPrevSlide();
                      return false;
                    });
                })
                </script>
                <div class="single_post">
                    <div class="section group">
        	        <div class="col span_6_of_12">
                        <a class="featured_img" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-big-thumb'); ?></a>
        	        </div>
                    <div class="col span_6_of_12">
                        <ul class="details">
                            <li>BY <?php the_author(); ?></li>
                            <li><?php the_time('F j, Y'); ?></li>
                            <li>IN <?php the_category(', ') ?></li>
                        </ul>
                        <h1><?php the_title(); ?></h1>
                        <p><?php the_excerpt_max_charlength(175); ?></p>
                        <?php
                            $pro_assign = get_post_meta( get_the_ID(), 'pro_assign', true );
                            if(!empty($pro_assign)) {
                                $pro_assign_array = $pro_assign;
                        ?>
                        <?php if(!empty($pro_assign_array)) { ?>
                        <div class="featured">
                            <h2>featured items</h2>
                            <div class="featured_slider_wrapper">
                                <div class="featured_slider_prev featured_slider_prev-<?php echo get_the_ID(); ?>"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_prev.png" /></a></div>
                                <div class="featured_slider_next featured_slider_next-<?php echo get_the_ID(); ?>"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_next.png" /></a></div>
                                <div class="featured_slider-<?php echo get_the_ID(); ?>">
                                    <?php foreach($pro_assign_array as $pro) { ?>
                                    <div><a href="<?php echo get_the_permalink($pro); ?>"><?php echo get_the_post_thumbnail($pro, 'product-small-thumb'); ?></a></div>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <a href="<?php the_permalink(); ?>" class="read_more">Read More</a>
                        <?php get_template_part('social_share'); ?>
                    </div>
                    </div>
                </div>
                <?php
                    endwhile;
                    endif;
                    wp_reset_postdata();
                ?>
                <?php
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 1,
                        'post_status' => 'publish',
                        'meta_key' => 'featured',
                        'meta_value' => 1,
                        'meta_compare' => '=',
                        'paged' => 5
                    );
                    $the_query = new WP_Query( $args );
                    if ( $the_query->have_posts() ) :
                    while ( $the_query->have_posts() ) : $the_query->the_post();
                ?>
                <script>
                jQuery(document).ready(function(){
                  var slider_<?php echo get_the_ID(); ?> = jQuery('.featured_slider-<?php echo get_the_ID(); ?>').bxSlider({
                    slideWidth: 63,
                    minSlides: 1,
                    maxSlides: 3,
                    slideMargin: 50,
                    pager: false,
                    controls: false,
                    onSliderLoad: function(){
                        jQuery(".featured_slider").css("visibility", "visible");
                      }
                  });
                  jQuery('.featured_slider_next-<?php echo get_the_ID(); ?> a').click(function(){
                      slider_<?php echo get_the_ID(); ?>.goToNextSlide();
                      return false;
                    });
                  jQuery('.featured_slider_prev-<?php echo get_the_ID(); ?> a').click(function(){
                      slider_<?php echo get_the_ID(); ?>.goToPrevSlide();
                      return false;
                    });
                })
                </script>
                <div class="single_post">
                    <div class="section group">
                    <div class="col span_6_of_12">
                        <ul class="details">
                            <li>BY <?php the_author(); ?></li>
                            <li><?php the_time('F j, Y'); ?></li>
                            <li>IN <?php the_category(', ') ?></li>
                        </ul>
                        <h1><?php the_title(); ?></h1>
                        <p><?php the_excerpt_max_charlength(175); ?></p>
                        <?php
                            $pro_assign = get_post_meta( get_the_ID(), 'pro_assign', true );
                            if(!empty($pro_assign)) {
                                $pro_assign_array = $pro_assign;
                        ?>
                        <?php if(!empty($pro_assign_array)) { ?>
                        <div class="featured">
                            <h2>featured items</h2>
                            <div class="featured_slider_wrapper">
                                <div class="featured_slider_prev featured_slider_prev-<?php echo get_the_ID(); ?>"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_prev.png" /></a></div>
                                <div class="featured_slider_next featured_slider_next-<?php echo get_the_ID(); ?>"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_next.png" /></a></div>
                                <div class="featured_slider-<?php echo get_the_ID(); ?>">
                                    <?php foreach($pro_assign_array as $pro) { ?>
                                    <div><a href="<?php echo get_the_permalink($pro); ?>"><?php echo get_the_post_thumbnail($pro, 'product-small-thumb'); ?></a></div>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <a href="<?php the_permalink(); ?>" class="read_more">Read More</a>
                        <?php get_template_part('social_share'); ?>
                    </div>
        	        <div class="col span_6_of_12">
                        <a class="featured_img" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-big-thumb'); ?></a>
        	        </div>
                    </div>
                </div>
                <?php
                    endwhile;
                    endif;
                    wp_reset_postdata();
                ?>
                <?php
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 1,
                        'post_status' => 'publish',
                        'meta_key' => 'featured',
                        'meta_value' => 1,
                        'meta_compare' => '=',
                        'paged' => 6
                    );
                    $the_query = new WP_Query( $args );
                    if ( $the_query->have_posts() ) :
                    while ( $the_query->have_posts() ) : $the_query->the_post();
                ?>
                <script>
                jQuery(document).ready(function(){
                  var slider_<?php echo get_the_ID(); ?> = jQuery('.featured_slider-<?php echo get_the_ID(); ?>').bxSlider({
                    slideWidth: 63,
                    minSlides: 1,
                    maxSlides: 3,
                    slideMargin: 50,
                    pager: false,
                    controls: false,
                    onSliderLoad: function(){
                        jQuery(".featured_slider").css("visibility", "visible");
                      }
                  });
                  jQuery('.featured_slider_next-<?php echo get_the_ID(); ?> a').click(function(){
                      slider_<?php echo get_the_ID(); ?>.goToNextSlide();
                      return false;
                    });
                  jQuery('.featured_slider_prev-<?php echo get_the_ID(); ?> a').click(function(){
                      slider_<?php echo get_the_ID(); ?>.goToPrevSlide();
                      return false;
                    });
                })
                </script>
                <div class="single_post">
                    <div class="section group">
        	        <div class="col span_6_of_12">
                        <a class="featured_img" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-big-thumb'); ?></a>
        	        </div>
                    <div class="col span_6_of_12">
                        <ul class="details">
                            <li>BY <?php the_author(); ?></li>
                            <li><?php the_time('F j, Y'); ?></li>
                            <li>IN <?php the_category(', ') ?></li>
                        </ul>
                        <h1><?php the_title(); ?></h1>
                        <p><?php the_excerpt_max_charlength(175); ?></p>
                        <?php
                            $pro_assign = get_post_meta( get_the_ID(), 'pro_assign', true );
                            if(!empty($pro_assign)) {
                                $pro_assign_array = $pro_assign;
                        ?>
                        <?php if(!empty($pro_assign_array)) { ?>
                        <div class="featured">
                            <h2>featured items</h2>
                            <div class="featured_slider_wrapper">
                                <div class="featured_slider_prev featured_slider_prev-<?php echo get_the_ID(); ?>"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_prev.png" /></a></div>
                                <div class="featured_slider_next featured_slider_next-<?php echo get_the_ID(); ?>"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_next.png" /></a></div>
                                <div class="featured_slider-<?php echo get_the_ID(); ?>">
                                    <?php foreach($pro_assign_array as $pro) { ?>
                                    <div><a href="<?php echo get_the_permalink($pro); ?>"><?php echo get_the_post_thumbnail($pro, 'product-small-thumb'); ?></a></div>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <a href="<?php the_permalink(); ?>" class="read_more">Read More</a>
                        <?php get_template_part('social_share'); ?>
                    </div>
                    </div>
                </div>
                <?php
                    endwhile;
                    endif;
                    wp_reset_postdata();
                ?>
	        </div>
            <div class="col span_4_of_12">
                <div class="small_post_wrapper">
                    <?php
                    $designer_cats = apply_filters( 'taxonomy-images-get-terms', '', array(
                        'before'       => '',
                        'after'        => '',
                        'before_image' => '',
                        'after_image'  => '',
                        'image_size'   => 'blog-small-thumb',
                        //'post_id'      => 1234,
                        'taxonomy'     => 'gallery_category',
                    ) );
                    $i=1;
                    foreach ($designer_cats as $designer_cat){
                        if($i>9 && $i<=18){
                    ?>
                    <div class="small_post">
                        <a href="<?php echo esc_url( get_term_link( $designer_cat, $designer_cat->taxonomy ) ); ?>">
                        <?php echo wp_get_attachment_image( $designer_cat->image_id, 'blog-small-thumb' ); ?>
                        <div class="small_post_title_wrapper">
                            <div class="small_post_title">
                                <h1><?php echo $designer_cat->name; ?></h1>
                                <!--<p><?php the_excerpt_max_charlength('30'); ?></p>-->
                            </div>
                        </div>
                        </a>
                    </div>
                    <?php
                    }
                    $i++;
                    }
                    ?>
                </div>
            </div>
	    </div>
	</div>
</div>
<div id="insta">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12">
                <h1>Instagram</h1>
								<div id="pixlee_container"></div><script type="text/javascript">window.PixleeAsyncInit = function() {Pixlee.init({apiKey:'1t2BOZyyWt9Se0ORYBhV'});Pixlee.addSimpleWidget({widgetId:'6256'});};</script><script src="//instafeed.assets.pixlee.com/assets/pixlee_widget_1_0_0.js"></script>
	        </div>
	    </div>
	</div>
    <?php
    	$client_id = get_option('INSTAGRAM_CLIENT_ID');

    	if (isset($_GET['code'])) {
			$curl = curl_init();
			curl_setopt_array($curl, array(
		    		CURLOPT_RETURNTRANSFER => 1,
		    		CURLOPT_URL => 'https://api.instagram.com/oauth/access_token',
		    		CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:45.0) Gecko/20100101 Firefox/45.0',
		   		CURLOPT_POST => 1,
		    		CURLOPT_POSTFIELDS => array(
					client_id => $client_id,
		        		client_secret => 'cfece5dd8e1248749cc1be8f281f817c',
		        		grant_type => 'authorization_code',
		        		redirect_uri => 'http://internationalprom.com',
		        		code => $_GET['code'],
		    		)
			));
			var_dump($curl);
			$response = curl_exec($curl);
			var_dump($response);
			$json  = json_decode($response, TRUE);
			curl_close($curl);
			update_option('access_token', $json["access_token"]);
	?>
	<script>
		var currentState = history.state;
		history.replaceState(currentState, "", "http://internationalprom.com");
	</script>
	<?php }
    $access_token = "";
 	$access_token = get_option('access_token');

 	$curl1 = curl_init();
	curl_setopt_array($curl1, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => "https://api.instagram.com/v1/users/self/media/recent/?access_token=".$access_token."&count=10",
		CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:45.0) Gecko/20100101 Firefox/45.0'
	));
	$response1 = curl_exec($curl1);
	curl_close($curl1);
	$decoded_results = json_decode($response1, TRUE);
	if (isset($decoded_results['meta']['error_type'])) {
		//header('Location: https://api.instagram.com/oauth/authorize/?client_id='.$client_id.'&redirect_uri=http://internationalprom.com&response_type=code');
	}


        //$access_token = get_option('access_token');
        //$url = 'https://api.instagram.com/v1/tags/'.$tag.'/media/recent?client_id='.$client_id.'&access_token='.$access_token;
        //$all_result = processURL($url);
        //$decoded_results = json_decode($all_result, true);
        //print_r($decoded_results);

    ?>

    <?php if(!empty($decoded_results)) {?>
	<div class="maincontent ins noPadding">
	    <div class="section group">
	        <div class="col span_12_of_12">
                <?php foreach($decoded_results['data'] as $item){ ?>
                <div class="insta matchheight">
                    <?php
                        $image_link = $item['images']['low_resolution']['url'];
                        $text = $item['caption']['text'];
                    ?>
                    <img src="<?php echo $image_link; ?>" />
                    <div class="insta_hover">
                        <div class="insta_hover_inner">
                            <a href="javascript:void(0);"><?php echo the_excerpt_max_charlength_by_content(50, $text); ?></a>
                        </div>
                    </div>
                </div>
                <?php } ?>
	        </div>
	    </div>
	</div>
    <?php } ?>
    <!--
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12">
                <h6>We will use <a href="#">@INTERNATIONALPROM</a> <a href="#">#BEYOUTY</a></h6>
	        </div>
	    </div>
	</div>
    -->
</div>
<?php get_footer(); ?>
