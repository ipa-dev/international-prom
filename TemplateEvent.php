<?php /* Template Name: Event */ ?>
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
<div id="events">
	<div class="maincontent">
        <div class="events_list">
            <?php
            $args = array(
                'post_type' => 'event',
                'posts_per_page' => -1,
                'post_status' => 'publish'
            );
            $the_query = new WP_Query( $args );
            if ( $the_query->have_posts() ) :
            while ( $the_query->have_posts() ) : $the_query->the_post();
            ?>
           <div class="event">
               <div class="section group">
        	        <div class="col span_1_of_12"> 
                        <div class="event_img"><?php the_post_thumbnail('event-thumb'); ?></div>        
        	        </div>
        	        <div class="col span_3_of_12">
                        <?php
                            $format_in = 'Ymd';
                            $format_out = 'F j, Y';
                            $date = DateTime::createFromFormat($format_in, get_field('date'));
                        ?>
                        <div class="date"><?php echo $date->format( $format_out ); ?></div>                          
        	        </div>
        	        <div class="col span_6_of_12">  
                        <div class="event_details">
                            <p>
                                <strong><?php the_title(); ?></strong>
                                <br />
                                <?php the_excerpt_max_charlength(80); ?>
                            </p>
                        </div>                        
        	        </div>
        	        <div class="col span_2_of_12">
                        <div class="event_buy"><a href="<?php the_permalink(); ?>">View Details</a></div>                          
        	        </div>
        	    </div>
           </div>
           <?php            
            endwhile;
            endif;
            wp_reset_postdata();
            ?>
        </div>
	</div>
</div>
<?php get_footer(); ?>