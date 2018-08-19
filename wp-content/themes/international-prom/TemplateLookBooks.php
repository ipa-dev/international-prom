<?php /* Template Name: Look Book */ ?>
<?php get_header(); ?>
<div id="content">
	<div class="maincontent">
		<div class="section group">
			<div class="col span_12_of_12"> 
				<div class="container">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
					<div><?php the_content(); ?></div>
					<?php endwhile; ?>
				</div>                         
			</div>
		</div>
	</div>
</div>
<div id="case">
	<div class="maincontent">
		<?php
			$paged     = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$args      = array(
				'post_type'      => 'look_book',
				'posts_per_page' => 12,
				'post_status'    => 'publish',
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) :
				?>
		<div class="section group">
				<?php
				while ( $the_query->have_posts() ) :
					$the_query->the_post();
					?>
			<div class="col span_3_of_12 matchheight">
				<div class="case_book">
					<div class="book_img">
						<?php the_post_thumbnail( 'look_book-thumb' ); ?>
						<div class="book_hover" style="padding: 67% 0;"><a href="<?php the_permalink(); ?>"><img src="<?php bloginfo( 'template_directory' ); ?>/images/book_view.png" /></a></div>
					</div>
				</div>
				<p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>                      
			</div>
		<?php endwhile; ?>
		</div>
				<?php
			endif;
			wp_reset_postdata();
			?>
		<div class="section group">
			<div class="col span_12_of_12">
				<div class="post_navi">
				<?php
				if ( function_exists( 'wp_pagenavi' ) ) {
					wp_pagenavi( array( 'query' => $the_query ) ); }
				?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
