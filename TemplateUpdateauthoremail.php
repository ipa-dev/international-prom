<?php 
/* Template Name: Update Author Email */
$args = array(
    'post_type' => 'retailer',
    'posts_per_page' => -1,
    'post_status' => 'publish'
);
$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) :
while ( $the_query->have_posts() ) : $the_query->the_post();
    echo $user_email = get_the_author_meta( 'user_email' );
    echo '<br />';
    update_post_meta( get_the_ID(), 'author_email', $user_email );
endwhile;
endif;
wp_reset_postdata();