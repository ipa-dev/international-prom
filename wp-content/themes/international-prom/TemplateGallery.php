<?php /* Template Name: Gallery */ ?>
<?php get_header(); ?>
<div id="content">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12"> 
                <div class="container">
                    <?php while (have_posts()) : the_post(); ?>
                    <h1><?php the_title(); ?></h1>
                    <p><?php the_content(); echo $post->post_content; ?></p>
                    <?php endwhile; ?>
                    <?php if($_GET['upload'] == "success") { ?>
                        <p class="successMsg">Images Uploaded...</p>
                    <?php } ?>
                    <!--
                    <p style="text-align: center;">
                        <a class="custom_button fancybox_gallery" href="#gallery_upload">Upload Your Pic</a>
                    </p>
                    -->  
                </div>                         
	        </div>
	    </div>
	</div>
</div>
<div id="gallery-refresh">
<?php
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    
    $args = array(
        'post_type' => 'gallery',
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'meta_query' => array(
	        'relation' => 'OR',
	        array(
		        'key' => 'view',
		        'compare' => 'NOT EXISTS',
		        'value' => ''
	        ),
	        array(
		        'key' => 'view',
		        'value' => 1,
		        'type' => 'NUMERIC',
		        'compare' => '!=',
	        ),
        ),
        'paged' => $paged,
        'orderby' => 'title',
        'order' => 'ASC'
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
                        <?php $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
                        <a class="gallery_img" rel="gallery1" href="<?php echo $url; ?>" title="<a style='color: #FFFFFF;' href='<?php the_permalink(); ?>'><?php the_title(); ?></a>"><?php the_post_thumbnail('gallery-thumb') ?></a>
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
    endif;
    wp_reset_postdata();
?>
</div>
<?php get_footer(); ?>