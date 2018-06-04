<?php get_header(); ?>
<div id="content">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12"> 
                <div class="container">
                    <?php while (have_posts()) : the_post(); ?>
                    <h1><?php the_title(); ?></h1>
                    <div style="text-align: center;"><?php the_post_thumbnail('event-thumb-full'); ?></div>
                    <?php
                        /*
                        $format_in = 'm-d-Y';
                        $format_out = 'F j, Y';
                        $date = DateTime::createFromFormat($format_in, get_field('date'));
                        */
                    ?>
                    <div class="date" style="margin: 15px 0;"><p><strong>Date: </strong><?php echo the_field('date');//echo $date->format( $format_out ); ?></p></div>
                    <?php
                        /*
                        $format_in = 'H:i';
                        $format_out = 'h:i a';
                        $time = DateTime::createFromFormat($format_in, get_field('time'));
                        */
                    ?>
                    <div class="date" style="margin: 15px 0;"><p><strong>Time: </strong><?php echo the_field('time');//echo $time->format( $format_out ); ?></p></div>
                    <div><p><?php echo get_post_meta(get_the_ID(), 'where', true); ?></p></div>
                    <?php endwhile; ?>  
                </div>                         
	        </div>
	    </div>
	</div>
</div>
<?php get_footer(); ?>