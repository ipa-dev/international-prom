<div class="social_nav">
    <ul>
        <?php
            $site_name = get_bloginfo("template_directory");
        
        	$permalink = get_permalink();
            $image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );
        	$featured_image = $image[0];
        	$post_title = rawurlencode(get_the_title());
            $content = get_the_excerpt();
        ?>
        <li><a href="http://www.facebook.com/sharer.php?u=<?php echo $permalink; ?>&amp;images=<?php echo $featured_image; ?>" class="facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
        <li><a href="https://twitter.com/share?url=<?php echo $permalink; ?>" class="twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
        <!--<li><a href="#" target="_blank"><i class="fa fa-instagram"></i></a></li>-->
        <li><a href="https://pinterest.com/pin/create/button/?url=<?php echo $permalink; ?>&media=<?php echo $featured_image; ?>&description=<?php echo $post_title; ?>" target="_blank"><i class="fa fa-pinterest"></i></a></li>
        <li><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $permalink; ?>&title=<?php echo $post_title; ?>&summary=<?php echo $content; ?>&source=" target="_blank"><i class="fa fa-linkedin"></i></a></li>
        <li><a href="<?php bloginfo('url'); ?>/rss/" target="_blank"><i class="fa fa-rss"></i></a></li>
    </ul>
</div>