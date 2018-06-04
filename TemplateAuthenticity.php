<?php /* Template Name: Authenticity */ ?>
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
<div id="gallery-refresh">
<?php
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'authenticity',
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'paged' => $paged
    );
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) :
?>
<div id="gallery">
	<div class="maincontent">
	    <div class="section group">
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
    	        <div class="col span_3_of_12">
                    <div class="gallery_wrapper matchheight">
                        <?php
                            $fav = get_user_meta($user_ID, 'fav', true);
                            $fav_array = json_decode($fav);
                            if(!empty($fav_array)) {
                                if(in_array(get_the_ID(), $fav_array)) {
                                    $isfav = 'active';
                                ?>
                                    <script>
                                        jQuery(document).ready(function() {
                                           jQuery('a#fav-<?php echo get_the_ID(); ?>').bind('click', removeFav<?php echo get_the_ID(); ?>); 
                                           jQuery('a#fav-<?php echo get_the_ID(); ?>').addClass('active');
                                        });
                                    </script>
                                <?php
                                }
                            }
                        ?>
                        <div class="fav">
                            <?php if(is_user_logged_in()) { ?>
                            <a href="javascript:void(0)" id="fav-<?php echo get_the_ID(); ?>" <?php if($isfav == 'active') { ?>onclick="removeFav<?php echo get_the_ID(); ?>()"<?php } else { ?>onclick="addFav<?php echo get_the_ID(); ?>()"<?php } ?> title="[+] Add as favorite"><i class="fa fa-heart"></i></a>
                            <?php } else { ?>
                            <a href="#login-popup" class="fancybox_login_popup"><i class="fa fa-heart"></i></a>
                            <?php } ?>
                        </div>
                        <?php $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
                        <a class="gallery_img" rel="gallery1" href="<?php echo $url; ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('gallery-thumb') ?></a>
                    </div> 
                    <script>
                    function addFav<?php echo get_the_ID(); ?>(){
                        var articleID = <?php echo get_the_ID(); ?>;
                        jQuery.ajax({
                          url: "<?php bloginfo('url'); ?>/favorite-add/",
                          data: {"id": articleID},
                          success: function(){
                               jQuery('a#fav-<?php echo get_the_ID(); ?>')
                                     .addClass('active')
                                     .attr('title','[-] Remove from favorites')
                                     .unbind('click')
                                     .bind('click', removeFav<?php echo get_the_ID(); ?>())
                               ;
                          }
                        });
                    }
                    function removeFav<?php echo get_the_ID(); ?>(){
                        var articleID = <?php echo get_the_ID(); ?>;
                        jQuery.ajax({
                          url: "<?php bloginfo('url'); ?>/favorite-remove/",
                          data: {"id": articleID},
                          success: function(){
                                jQuery('a#fav-<?php echo get_the_ID(); ?>')
                                     .removeClass('active')
                                     .attr('title','[+] Add as favorite')
                                     .unbind('click')
                                     .bind('click', addFav<?php echo get_the_ID(); ?>)
                                ;
                          }
                        });
                    }
                    //this will make the link listen to function addFav (you might know this already)
                    //jQuery('a#fav').bind('click', addFav);
                    </script>                         
    	        </div>
            <?php endwhile; ?>
	    </div>
        <div class="section group">
            <div class="col span_12_of_12">
                <div class="post_navi"><?php if(function_exists('wp_pagenavi')) { wp_pagenavi(array( 'query' => $the_query )); } ?></div>
            </div>
        </div>
	</div>
</div>
<?php
    endif;
    wp_reset_postdata();
?>
</div>
<?php get_footer(); ?>