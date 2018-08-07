<?php /* Template Name: TV */ ?>
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
<?php
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'tv',
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'paged' => $paged
    );
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) :
?>
<div id="tv">
	<div class="maincontent">
	    <div class="section group">
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
    	        <div class="col span_3_of_12 matchheight">
                    <div class="tv_wrapper">
                        <?php
                            $youtube_url = get_field('youtube_video_url');
                            $vimeo_url = get_field('vimeo_video_url');
                            if(!empty($youtube_url)) {
                                $url = $youtube_url;
                                if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id)) {
                                  $values = $id[1];
                                } else if (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $id)) {
                                  $values = $id[1];
                                } else if (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $id)) {
                                  $values = $id[1];
                                } else if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $id)) {
                                  $values = $id[1];
                                }
                                else if (preg_match('/youtube\.com\/verify_age\?next_url=\/watch%3Fv%3D([^\&\?\/]+)/', $url, $id)) {
                                    $values = $id[1];
                                } else {   
                                // not an youtube video
                                }
                                $img = 'https://i1.ytimg.com/vi/'.$values.'/hqdefault.jpg';
                                $url = 'https://www.youtube.com/embed/'.$values.'?autoplay=1';
                            }
                            if(!empty($vimeo_url)) {
                                $url = $vimeo_url;
                                $values = get_vimeoid($url);
               
                                $hash = unserialize(processURL("http://vimeo.com/api/v2/video/$values.php"));
                                $img = $hash[0]['thumbnail_large'];
                                $url = 'https://player.vimeo.com/video/'.$values.'?title=0&amp;byline=0&amp;portrait=0'; 
                            }
                        ?>
                        <a class="fancybox_video fancybox.iframe" href="<?php echo $url; ?>" title="<?php the_title(); ?>"><img src="<?php echo $img; ?>" /></a>
                    </div> 
                    <h2><?php the_title(); ?></h2>                         
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
<?php get_footer(); ?>