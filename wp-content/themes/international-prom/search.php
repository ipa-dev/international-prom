<?php get_header(); ?>
<div id="content">
    <div class="maincontent noPadding">
        <div class="section group">
            <div class="col span_12_of_12">
                    <h1>Search Results: &quot;<span><?php echo get_search_query(); ?></span>&quot;</h1>
                    <?php if ( have_posts() ) :  // results found?>
                    	<?php while ( have_posts() ) : the_post(); ?>
                    		<div class="search_result">
                    			<h2><a href="<?php the_permalink(); ?>"><?php the_title();  ?></a></h2>
                                <?php if(has_post_thumbnail()) { ?>
                                    <div class="section group">
                                        <div class="col span_3_of_12">
                                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                                        </div>
                                        <div class="col span_9_of_12">
                                            <?php the_excerpt(); ?>
                                        </div>                                        
                                    </div>
                                <?php } else { ?>
                                    <?php the_excerpt(); ?>
                                <?php } ?>
                                <hr />
                    		</div>
                    	<?php endwhile; ?>
                    <?php else :  // no results?>
                    	<div class="search_result">
                    		<h2>No Results Found.</h2>
                    	</div>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>