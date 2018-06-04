<?php get_header(); ?>
<div id="content">
	<div class="maincontent">

        <?php while (have_posts()) : the_post(); ?>
	    <div class="section group">
            <?php $black_white = get_field('black_white'); ?>
            <?php if($black_white != 'white') { ?>
            <div class="col span_6_of_12">
                <div class="pro_img_single matchheight"><?php the_post_thumbnail('full'); ?></div>
            </div>
	        <div class="col span_6_of_12"> 
                <div class="container single_post matchheight">
                    <ul class="details">
                        <li>BY <?php the_author(); ?></li>
                        <li><?php the_time('F j, Y'); ?></li>
                        <li>IN <?php the_category(', ') ?></li>
                    </ul>
                    <h1><?php the_title(); ?></h1>
                    <div><?php the_content(); ?></div>
                    <?php
                        $pro_assign = get_post_meta( get_the_ID(), 'pro_assign', true );
                        if(!empty($pro_assign)) {
                            $pro_assign_array = $pro_assign;
                    ?>
                    <?php if(!empty($pro_assign_array)) { ?>
                    <div class="featured">
                        <h2>featured items</h2>
                        <div class="featured_slider_wrapper">
                            <div class="featured_slider_prev"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_prev.png" /></a></div>
                            <div class="featured_slider_next"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_next.png" /></a></div>
                            <div class="featured_slider">
                                <?php foreach($pro_assign_array as $pro) { ?>
                                <div><a href="<?php echo get_the_permalink($pro); ?>"><?php echo get_the_post_thumbnail($pro, 'product-small-thumb'); ?></a></div>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php get_template_part('social_share'); ?>
                </div>                         
	        </div>
            <?php } else { ?>
	        <div class="col span_6_of_12"> 
                <div class="container single_post matchheight">
                    <ul class="details">
                        <li>BY <?php the_author(); ?></li>
                        <li><?php the_time('F j, Y'); ?></li>
                        <li>IN <?php the_category(', ') ?></li>
                    </ul>
                    <h1><?php the_title(); ?></h1>
                    <div><?php the_content(); ?></div>
                    <?php
                        $pro_assign = get_post_meta( get_the_ID(), 'pro_assign', true );
                        if(!empty($pro_assign)) {
                            $pro_assign_array = $pro_assign;
                    ?>
                    <?php if(!empty($pro_assign_array)) { ?>
                    <div class="featured">
                        <h2>featured items</h2>
                        <div class="featured_slider_wrapper">
                            <div class="featured_slider_prev"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_prev.png" /></a></div>
                            <div class="featured_slider_next"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/featured_slider_next.png" /></a></div>
                            <div class="featured_slider">
                                <?php foreach($pro_assign_array as $pro) { ?>
                                <div><a href="<?php echo get_the_permalink($pro); ?>"><?php echo get_the_post_thumbnail($pro, 'product-small-thumb'); ?></a></div>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php get_template_part('social_share'); ?>
                </div>                         
	        </div>
            <div class="col span_6_of_12">
                <div class="pro_img_single matchheight"><?php the_post_thumbnail('full'); ?></div>
            </div>
            <?php } ?>
	    </div>
        <div class="section group">
            <div class="col span_6_of_12">
				<div id="reviews">
					<?php comments_template(); ?>
				</div>  
            </div>
        </div>
        <?php endwhile; ?>
	</div>
</div>
<script>
jQuery(document).ready(function(){
  var slider = jQuery('.featured_slider').bxSlider({
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
  jQuery('.featured_slider_next a').click(function(){
      slider.goToNextSlide();
      return false;
    });
  jQuery('.featured_slider_prev a').click(function(){
      slider.goToPrevSlide();
      return false;
    });
});
</script>
<?php get_footer(); ?>