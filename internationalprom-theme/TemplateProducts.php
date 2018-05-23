<?php /* Template Name: Products */ ?>
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
    session_start();
    
    if(isset($_POST['num_select'])) {
        $_SESSION['num_select'] = $_POST['num_select'];
    } else {
        if(!isset($_SESSION['num_select'])) {
            $_SESSION['num_select'] = 12;            
        }
    }
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'gallery',
        'posts_per_page' => $_SESSION['num_select'],
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
        'paged' => $paged
    );
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) :
?>
<div id="num_select">
	<div class="maincontent">
	    <div class="section group">
            <div class="col span_8_of_12">
                <p>
                    Displaying Products <?php echo ((($paged-1)*$_SESSION['num_select'])+1); ?> - <?php echo ((($paged-1)*$_SESSION['num_select'])+$_SESSION['num_select']); ?> of <?php echo $the_query->found_posts; ?> results
                    <br />
                    Page <?php echo $paged; ?> of <?php echo $the_query->max_num_pages; ?>
                </p>
            </div>
	        <div class="col span_4_of_12" style="text-align: right;">
                <p style="display: inline-block; width: auto; margin-right: 20px;">
                    Show:
                </p>
                 
                <form style="display: inline-block; width: auto;" method="POST" action="">
                    <select style="display: inline-block; width: auto;" style="" name="num_select" onchange="this.form.submit()">
                        <option<?php if($_SESSION['num_select'] == 10) { echo ' selected="selected"'; } ?> value="10">10</option>
                        <option<?php if($_SESSION['num_select'] == 20) { echo ' selected="selected"'; } ?> value="20">20</option>
                        <option<?php if($_SESSION['num_select'] == 30) { echo ' selected="selected"'; } ?> value="30">30</option>
                        <option<?php if($_SESSION['num_select'] == 50) { echo ' selected="selected"'; } ?> value="50">50</option>
                        <option<?php if($_SESSION['num_select'] == 100) { echo ' selected="selected"'; } ?> value="100">100</option>
                    </select>
                </form>                         
	        </div>
	    </div>
	</div>
</div>
<div class="pro_inner">
	<div class="maincontent">
	    <div class="section group">	        
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
            <div class="col span_3_of_12">
            <div class="pro matchheight">
                <div id="propop-<?php echo get_the_ID(); ?>" style="display: none;">
                    <div class="propop_wrapper">
                        <?php the_post_thumbnail('propop-thumb') ?>
                        <div class="pro_content"><p><?php the_excerpt_max_charlength(120); ?></p></div>
                        <p>
                        <a class="custom_button" href="<?php the_permalink(); ?>">View Product</a>
                        <a class="custom_button" target="_blank" href="<?php bloginfo('url'); ?>/store-locator/">Find in a Store Near You</a>
                        </p>
                    </div>                            
                </div>
                <div class="pro_img_inner"><?php the_post_thumbnail('product-thumb-inner'); ?></div>
                <div class="pro_hover">
                    <div class="pro_hover_inner">
                        <!--<a class="product_img" href="#propop-<?php echo get_the_ID(); ?>" title="<?php the_title(); ?>">View Product</a>-->
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">View Product</a>                                
                    </div>
                </div>
            </div>
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