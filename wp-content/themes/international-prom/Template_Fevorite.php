<?php /* Template Name: Fevorites */ ?>
<?php if(is_user_logged_in()) { ?>
<?php global $user_ID; ?>
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
    $fav = get_user_meta($user_ID, 'fav', true);
    $favA = json_decode($fav);
    if(!empty($favA)) {
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'any',
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'post__in' => $favA,
        'paged' => $paged
    );
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) {
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
                                           //jQuery('a#fav-<?php //echo get_the_ID(); ?>').bind('click', removeFav<?php //echo get_the_ID(); ?>); 
                                           jQuery('a#fav-<?php echo get_the_ID(); ?>').addClass('active');
                                        });
                                    </script>
                                <?php
                                }
                            }
                        ?>
                        <div class="fav">
                            <?php if(is_user_logged_in()) { ?>
                            <a href="javascript:void(0)" id="fav-<?php echo get_the_ID(); ?>" <?php if($isfav == 'active') { ?>onclick="removeFav<?php echo get_the_ID(); ?>();" title="[-] Remove from favorites"<?php } else { ?>onclick="addFav<?php echo get_the_ID(); ?>();" title="[+] Add as favorite"<?php } ?>><i class="fa fa-heart"></i></a>
                            <?php } else { ?>
                            <a href="#login-popup" class="fancybox_login_popup"><i class="fa fa-heart"></i></a>
                            <?php } ?>
                        </div>
                        <?php //$url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                            <?php the_post_thumbnail('gallery-thumb') ?>
                        </a>
                        <h3 style="font-size: 18px;"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                    </div> 
                    <script>
                    function addFav<?php echo get_the_ID(); ?>(){
                        var articleID = <?php echo get_the_ID(); ?>;
                        jQuery.ajax({
                          url: "<?php bloginfo('url'); ?>/favorite-add/",
                          data: {"id": articleID},
                          success: function(response){
                            if(response == 'addSuccess') {
                               jQuery('a#fav-<?php echo get_the_ID(); ?>')
                                     .addClass('active')
                                     .attr('title','[-] Remove from favorites')
                                     .attr('onclick','removeFav<?php echo get_the_ID(); ?>();')
                               ;
                            }
                          }
                        });
                    }
                    function removeFav<?php echo get_the_ID(); ?>(){
                        var articleID = <?php echo get_the_ID(); ?>;
                        jQuery.ajax({
                          url: "<?php bloginfo('url'); ?>/favorite-remove/",
                          data: {"id": articleID},
                          success: function(response){
                            if(response == 'removeSuccess') {
                                jQuery('a#fav-<?php echo get_the_ID(); ?>')
                                     .removeClass('active')
                                     .attr('title','[+] Add as favorite')
                                     .unbind('click')
                                     .attr('onclick','addFav<?php echo get_the_ID(); ?>();')
                                ;
                                location.reload();
                            }
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
    }
    }
    else {
?>
<div class="maincontent">
    <div class="section group">
        <div class="col span_12_of_12">
            <p>No Favorites yet...</p>
        </div>
    </div>
</div>
<?php
    }
    wp_reset_postdata();
?>
</div>
<?php get_footer(); ?>
<?php } else {
   header('Location: '.get_bloginfo('home').'/sign-in/?role=shopper'); 
} ?>