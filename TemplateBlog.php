<?php /* Template Name: Blog Listing */ ?>
<?php get_header(); ?>
<div id="content">
	<div class="maincontent">
        <div class="section group">
	        <div class="col span_12_of_12">
                <div class="container">
                <?php
                if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                ?>
                <h1><?php the_title(); ?></h1>  
                <?php the_content(); ?>
                <?php            
                endwhile;
                endif;
                wp_reset_postdata();
                ?>        
                </div>                
	        </div>
	    </div>
	</div>
</div>

<?php
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'paged' => $paged
    );
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) :
?>
<div id="blogposts" class="blog_inner">
	<div class="maincontent">
	    <div class="section group">
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	        <div class="col span_4_of_12 matchheight">
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
                    <a class="featured_img" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-small-thumb'); ?></a>
                    <ul class="details">
                        <li>BY <?php the_author(); ?></li>
                        <li><?php the_time('F j, Y'); ?></li>
                        <li>IN <?php the_category(', ') ?></li>
                    </ul>
                    <h1><?php the_title(); ?></h1>
                    <p><?php the_excerpt_max_charlength(175); ?></p>
                    <a href="<?php the_permalink(); ?>" class="read_more">Read More</a>
                    <div class="social_nav">
                        <ul>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="#"><i class="fa fa-rss"></i></a></li>
                        </ul>
                    </div>
                </div>                         
	        </div>
            <?php endwhile; ?>
	    </div>
        <div class="section group">
            <div class="col span_12_of_12">
                <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(array( 'query' => $the_query )); } ?>
            </div>
        </div>
	</div>
</div>
<?php                
endif;
wp_reset_postdata(); 
?>
<?php get_footer(); ?>