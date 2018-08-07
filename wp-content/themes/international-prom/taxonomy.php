<?php get_header(); ?>
<div id="content">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12"> 
                <div class="container">
                    <h1><?php single_cat_title(); ?></h1>
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
    session_start();
    global $wp_query;

    if(isset($_POST['num_select'])) {
        $_SESSION['num_select'] = $_POST['num_select'];
    } else {
        if(!isset($_SESSION['num_select'])) {
            $_SESSION['num_select'] = 12;
        }
    }
    if($_POST['reset'] == 'Reset') {
        unset($_SESSION['order_select']);
        unset($_POST['order_select']);
    }
    if(isset($_POST['order_select'])) {
        $_SESSION['order_select'] = $_POST['order_select'];
        if($_POST['order_select'] == 'date_asc') {
	        query_posts(array_merge($wp_query->query, array(
		        'paged'          => get_query_var('paged'),
		        'posts_per_page' => $_SESSION['num_select'],
		        'orderby' => 'date',
		        'order' => 'ASC',
		        /*'meta_query' => array(
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
		        )*/
	        )));
        }
	    if($_POST['order_select'] == 'date_desc') {
		    query_posts(array_merge($wp_query->query, array(
			    'paged'          => get_query_var('paged'),
			    'posts_per_page' => $_SESSION['num_select'],
			    'orderby' => 'date',
			    'order' => 'DESC',
			    /*'meta_query' => array(
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
			    )*/
		    )));
	    }
	    if($_POST['order_select'] == 'style_no') {
		    query_posts(array_merge($wp_query->query, array(
			    'paged'          => get_query_var('paged'),
			    'posts_per_page' => $_SESSION['num_select'],
			    /*'meta_key'  => 'style_no',
			    'orderby'   => 'meta_value',
			    'order' => 'ASC',*/
			    'meta_query' => array(
				    'relation' => 'OR',
				    /*array(
					    'key' => 'view',
					    'compare' => 'NOT EXISTS',
					    'value' => ''
				    ),
				    array(
					    'key' => 'view',
					    'value' => 1,
					    'type' => 'NUMERIC',
					    'compare' => '!=',
				    ),*/
				    'style_clause' => array(
					    'key' => 'style_no',
					    'compare' => 'EXISTS',
				    ),
			    ),
			    'orderby' => array(
				    'style_clause' => 'ASC',
			    ),
		    )));
	    }
    } else {
	    $_SESSION['order_select'] = 'name_asc';
	    query_posts(array_merge($wp_query->query, array(
		    'paged'          => get_query_var('paged'),
		    'posts_per_page' => $_SESSION['num_select'],
		    /*'orderby' => 'title',
		    'order' => 'ASC',*/
		    'meta_query' => array(
			    'relation' => 'OR',
			    /*array(
				    'key' => 'view',
				    'compare' => 'NOT EXISTS',
				    'value' => ''
			    ),
			    array(
				    'key' => 'view',
				    'value' => 1,
				    'type' => 'NUMERIC',
				    'compare' => '!=',
			    ),*/
			    'order_clause' => array(
				    'key' => 'order',
				    'compare' => 'EXISTS',
			    ),
		    ),
		    'orderby' => array(
			    'order_clause' => 'ASC',
		    ),
	    )));
    }
    $paged = get_query_var('paged');
    if($paged == 0) {
        $paged = $paged+1;
    }
    //print_r($wp_query);
    if ( have_posts() ) :
    //print_r($wp_query);
?>
<div id="num_select">
	<div class="maincontent">
	    <div class="section group">
            <div class="col span_6_of_12">
                <p>
                    Displaying Products <?php echo ((($paged-1)*$_SESSION['num_select'])+1); ?> - <?php echo ((($paged-1)*$_SESSION['num_select'])+$_SESSION['num_select']); ?> of <?php echo $the_query->found_posts; ?> results
                    <br />
                    Page <?php echo $paged; ?> of <?php echo $wp_query->max_num_pages; ?>
                </p>
            </div>
            <div class="col span_4_of_12" style="text-align: right;">
                <p style="display: inline-block; width: auto; margin-right: 20px;">
                    Sort by:
                </p>

                <form style="display: inline-block; width: auto;" method="POST" action="">
                    <select style="display: inline-block; width: auto;" style="" name="order_select" onchange="this.form.submit()">
                        <option<?php if($_SESSION['order_select'] == 'name_asc') { echo ' selected="selected"'; } ?> value="name_asc">Title</option>
                        <option<?php if($_SESSION['order_select'] == 'date_asc') { echo ' selected="selected"'; } ?> value="date_asc">Oldest</option>
                        <option<?php if($_SESSION['order_select'] == 'date_desc') { echo ' selected="selected"'; } ?> value="date_desc">Newest</option>
                        <option<?php if($_SESSION['order_select'] == 'style_no') { echo ' selected="selected"'; } ?> value="style_no">Style Number</option>
                    </select>
                    <input style="padding: 7.5px 15px; font-size: 16px; vertical-align: top; border-radius: 0;" type="submit" name="reset" value="Reset"/>
                </form>
            </div>
	        <div class="col span_2_of_12" style="text-align: right;">
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
<div id="gallery">

	<div class="maincontent">
	    <div class="section group">
            <?php while ( have_posts() ) : the_post(); ?>
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
                        <?php //$url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                            <?php the_post_thumbnail('gallery-thumb') ?>
                        </a>
                        <h3 style="font-size: 18px;"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
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
                <div class="post_navi"><?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?></div>
            </div>
        </div>
        <?php if (single_cat_title('', false) == 'Sherri Hill') { ?>
        <div class="section group">
            <div class="col span_12_of_12">
            	 <script type="text/javascript">
		        var _currentScript = document.currentScript || null;
		    
		        (function () {
		            var script = document.createElement('script');
		            script.type = 'text/javascript';
		            script.src = '//d1onrt0vmy4bx9.cloudfront.net/webseal.js';
		            script.async = true;
		    
		            var firstScript = document.getElementsByTagName('script')[0];
		            firstScript.parentNode.insertBefore(script, firstScript)
		        })();
		 </script>
            </div>
        </div>
        <?php } ?>
	</div>
</div>
<?php
    endif;
    wp_reset_postdata();
?>
</div>
<?php get_footer(); ?>