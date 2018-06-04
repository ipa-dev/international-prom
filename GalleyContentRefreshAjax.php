<?php /* Template Name: Gallery Content Refresh Ajax */ ?>
<?php
global $user_ID;
if($_SERVER['REQUEST_METHOD'] == "POST"){
?>
<?php
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'gallery',
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
                    <div class="gallery_wrapper">
                        <?php $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
                        <a class="gallery_img" rel="gallery1" href="<?php echo $url; ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('gallery-thumb') ?></a>
                    </div>                          
    	        </div>
            <?php endwhile; ?>
	    </div>
	</div>
</div>
<?php
    endif;
    wp_reset_postdata();
?>
<?php
}
?>