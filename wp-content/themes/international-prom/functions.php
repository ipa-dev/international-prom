<?php
require_once __DIR__ . '/vendor/autoload.php';
( new \Dotenv\Dotenv( __DIR__ . '/' ) )->load();

update_option( 'INSTAGRAM_REDIRECT_URI', get_bloginfo( 'url' ) . '/instagram-authorization/' );

update_option( 'PINTEREST_REDIRECT_URI', get_bloginfo( 'url' ) . '/pinterest-authorization/' );

update_option( 'TWITTER_CALLBACK_URL', get_bloginfo( 'url' ) . '/twitter-authorization/' );

update_option( 'MAILCHIMP_REDIRECT_URI', get_bloginfo( 'url' ) . '/mailchimp-authorization/' );

if ( ! function_exists( 'wp_password_change_notification' ) ) {
	function wp_password_change_notification() {}
}
add_action('wp_ajax_stripe_stripePayment', 'stripePayment');

function stripePayment() {
	global $user_ID;
	require_once(TEMPLATEPATH.'/stripe-php/config.php');
	if ($_POST['stripeAmount']) {
		$amount   = $_POST['stripeAmount'] * 100;
		$token    = $_POST['stripeToken'];
		$customer = \Stripe\Customer::create( array(
			'email' => $_POST['stripeEmail'],
			'card'  => $token
		) );
		$charge   = \Stripe\Charge::create( array(
			'customer'    => $customer->id,
			'amount'      => $amount,
			'description' => '',
			'currency'    => 'usd'
		) );
		if ( $charge ) {
			update_user_meta($user_ID, 'text_limit', $_POST['text_limit']);
			echo get_user_meta($user_ID, 'text_limit', true);
		}
	}
	wp_die();
}


add_filter( 'wp_mail_from_name', 'new_mail_from_name' );
function new_mail_from_name( $old ) {
	$site_title = get_bloginfo( 'name' );
	return $site_title;
}

register_nav_menus(
	array(
		'mainmenu'   => __( 'Main Menu' ),
		'footermenu' => __( 'Footer Menu' ),
		'accnav'     => __( 'Account Navigation' ),
	)
);

require_once 'acf-country/acf-country.php';

/*
register_sidebar(array('name'=>'Blog Sidebar',
'before_widget' => '<div class="sidebar_content">',
'after_widget' => '</div>',
'before_title' => '<h2>',
'after_title' => '</h2>',
));
*/
register_sidebar(
	array(
		'name'          => 'Logo',
		'id'            => 'logo',
		'before_widget' => '<div class="logo">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 style="display: none;">',
		'after_title'   => '</h2>',
	)
);
register_sidebar(
	array(
		'name'          => 'Footer Logo',
		'id'            => 'footer-logo',
		'before_widget' => '<div class="logo">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 style="display: none;">',
		'after_title'   => '</h2>',
	)
);
register_sidebar(
	array(
		'name'          => 'Footer About',
		'id'            => 'footer-about',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 style="display: none;">',
		'after_title'   => '</h2>',
	)
);
register_sidebar(
	array(
		'name'          => 'Newsletter',
		'id'            => 'newsletter',
		'before_widget' => '<div class="newsletter_wrapper">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 style="display: none;">',
		'after_title'   => '</h2>',
	)
);

add_theme_support( 'post-thumbnails' );

add_image_size( 'blog-big-thumb', 399, 551, array( 'center', 'top' ) );
add_image_size( 'blog-small-thumb', 230, 178, array( 'center', 'top' ) );

add_image_size( 'pro-thumbnail', 170, 170, array( 'center', 'top' ) );

add_role( 'customer', 'Customer' );
add_role( 'retailer', 'Retailer' );

function encripted( $data ) {
	$key1       = '644CBEF595BC9';
	$final_data = $key1 . '|' . $data;
	$val        = base64_encode( base64_encode( base64_encode( $final_data ) ) );
	return $val;
}
function decripted( $data ) {
	$val        = base64_decode( base64_decode( base64_decode( $data ) ) );
	$final_data = explode( '|', $val );
	return $final_data[1];
}
function get_user_role( $userid ) {
	$user_info = get_userdata( $userid );
	$role      = implode( ', ', $user_info->roles );
	return $role;
}
if ( ! current_user_can( 'administrator' ) ) :
	show_admin_bar( false );
endif;
function get_locked_counter() {
	global $wpdb;
	$lock_date   = date( 'Y-m-d H:i:s' );
	$ip          = $_SERVER['REMOTE_ADDR'];
	$table_name  = $wpdb->prefix . 'ip_lock';
	$query       = "SELECT * FROM $table_name WHERE ip = '" . $ip . "'";
	$result      = $wpdb->get_row( $query );
	$start_date  = new DateTime( $result->locking_time );
	$since_start = $start_date->diff( new DateTime( $lock_date ) );
	$total_min   = $since_start->i;
	if ( $total_min > 10 ) {
		$query2 = "UPDATE $table_name SET attempts = 0 WHERE ip = '" . $ip . "'";
		$wpdb->query( $query2 );
		return 0;
	} else {
		return $result->attempts;
	}
}
function update_locked_counter() {
	global $wpdb;
	$lock_date  = date( 'Y-m-d H:i:s' );
	$ip         = $_SERVER['REMOTE_ADDR'];
	$table_name = $wpdb->prefix . 'ip_lock';
	$query      = "SELECT * FROM $table_name WHERE ip = '" . $ip . "'";
	$result     = $wpdb->get_row( $query );
	$attempts   = $result->attempts + 1;
	if ( $result->ip == $ip ) {
		$query2 = "UPDATE $table_name SET locking_time = '" . $lock_date . "', attempts = " . $attempts . " WHERE ip = '" . $ip . "'";
	} else {
		$query2 = "INSERT INTO $table_name (ip, locking_time, attempts) VALUES('" . $ip . "', '" . $lock_date . "', '" . $attempts . "')";
	}
	$result = $wpdb->query( $query2 );
}
function content( $limit, $postid ) {
	$post        = get_post( $postid );
	$fullContent = $post->post_content;
	$content     = explode( ' ', $fullContent, $limit );
	if ( count( $content ) >= $limit ) {
		array_pop( $content );
		$content = implode( ' ', $content ) . '...';
	} else {
		$content = implode( ' ', $content );
	}
	$content = preg_replace( '/\[.+\]/', '', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	return $content;
}
function the_excerpt_max_charlength( $charlength ) {
	$excerpt = get_the_excerpt();
	$charlength++;
	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex   = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut   = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '...';
	} else {
		echo $excerpt;
	}
}
function get_the_excerpt_max_charlength( $charlength ) {
	$excerpt = get_the_excerpt();
	$content = '';
	$charlength++;
	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex   = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut   = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			$content = mb_substr( $subex, 0, $excut );
		} else {
			$content = $subex;
		}
		$content = '...';
	} else {
		$content = $excerpt;
	}
	return $content;
}
function the_excerpt_max_charlength_by_content( $charlength, $content ) {
	$excerpt = $content;
	$charlength++;
	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex   = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut   = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '...';
	} else {
		echo $excerpt;
	}
}
/* This is for Custom Post Type
add_action('init', 'custom_register_function');
function custom_register_function(){
	$labels = array(
		'name' => _x('Custom Post Type', 'post type general name'),
		'singular_name' => _x('Custom Post Type', 'post type singular name'),
		'add_new' => _x('Add New', 'Custom Post Type item'),
		'add_new_item' => __('Add New Custom Post Type'),
		'edit_item' => __('Edit Custom Post Type Item'),
		'new_item' => __('New Custom Post Type Item'),
		'view_item' => __('View Custom Post Type Item'),
		'search_items' => __('Search Custom Post Type'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'customlic' => true,
		'customlicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'public' => true,
		//'menu_icon' => plugin_dir_url( __FILE__ ) .'/images/Custom Post Type.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array('title', 'editor', 'thumbnail'),
		'taxonomies' => array('custom_category')
	);
register_post_type( 'custom' , $args );
}
*/
/* This is for Gallery */
register_taxonomy(
	'gallery_category', 'gallery', array(
		'hierarchical'          => true,
		'label'                 => 'Designer Category',
		'singular_label'        => 'Designer Category',
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array(
			'slug'       => 'gallery_category',
			'with_front' => false,
		),
		'adlic'                 => true,
		'show_ui'               => true,
		'show_tagcloud'         => true,
		'_builtin'              => true,
		'show_in_nav_menus'     => true,
	)
);
add_action( 'init', 'gallery_register_function' );
function gallery_register_function() {
	$labels = array(
		'name'               => _x( 'Designer', 'post type general name' ),
		'singular_name'      => _x( 'Designer', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'Designer item' ),
		'add_new_item'       => __( 'Add New Designer' ),
		'edit_item'          => __( 'Edit Designer Item' ),
		'new_item'           => __( 'New Designer Item' ),
		'view_item'          => __( 'View Designer Item' ),
		'search_items'       => __( 'Search Designer' ),
		'not_found'          => __( 'Nothing found' ),
		'not_found_in_trash' => __( 'Nothing found in Trash' ),
		'parent_item_colon'  => '',
	);
	$args   = array(
		'labels'                 => $labels,
		'gallerylic'             => true,
		'gallerylicly_queryable' => true,
		'show_ui'                => true,
		'query_var'              => true,
		'public'                 => true,
		'menu_icon'              => get_bloginfo( 'template_directory' ) . '/images/Gallery.png',
		'rewrite'                => true,
		'capability_type'        => 'post',
		'hierarchical'           => false,
		'supports'               => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'             => array( 'gallery_category' ),
	);
	register_post_type( 'gallery', $args );
}
add_image_size( 'gallery-thumb', 400, 600, array( 'center', 'top' ) );

/* This is for Product */
/*register_taxonomy('product_category', 'product',array("hierarchical" => true,"label" => "Product Category","singular_label" => "Product Category",'update_count_callback' => '_update_post_term_count','query_var' => true,'rewrite' => array( 'slug' => 'product_category', 'with_front' => false ),'adlic' => true,'show_ui' => true,'show_tagcloud' => true,'_builtin' => true,'show_in_nav_menus' => true));
add_action('init', 'product_register_function');
function product_register_function(){
	$labels = array(
		'name' => _x('Product', 'post type general name'),
		'singular_name' => _x('Product', 'post type singular name'),
		'add_new' => _x('Add New', 'Product item'),
		'add_new_item' => __('Add New Product'),
		'edit_item' => __('Edit Product Item'),
		'new_item' => __('New Product Item'),
		'view_item' => __('View Product Item'),
		'search_items' => __('Search Product'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'productlic' => true,
		'productlicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'public' => true,
		'menu_icon' => get_bloginfo('template_directory').'/images/Product.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array('title', 'editor', 'thumbnail'),
		'taxonomies' => array('product_category')
	);
register_post_type( 'product' , $args );
}
*/
add_image_size( 'product-thumb', 400, 400, array( 'center', 'top' ) );
add_image_size( 'product-thumb-inner', 280, 385, array( 'center', 'top' ) );
add_image_size( 'product-slider-thumb', 183, 250, array( 'center', 'top' ) );
add_image_size( 'product-small-thumb', 60, 80, array( 'center', 'top' ) );
add_image_size( 'propop-thumb', 250, 250, array( 'center', 'top' ) );

/* This is for Retailer */
add_action( 'init', 'retailer_register_function' );
function retailer_register_function() {
	$labels = array(
		'name'               => _x( 'Retailer', 'post type general name' ),
		'singular_name'      => _x( 'Retailer', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'Retailer item' ),
		'add_new_item'       => __( 'Add New Retailer' ),
		'edit_item'          => __( 'Edit Retailer Item' ),
		'new_item'           => __( 'New Retailer Item' ),
		'view_item'          => __( 'View Retailer Item' ),
		'search_items'       => __( 'Search Retailer' ),
		'not_found'          => __( 'Nothing found' ),
		'not_found_in_trash' => __( 'Nothing found in Trash' ),
		'parent_item_colon'  => '',
	);
	$args   = array(
		'labels'                  => $labels,
		'retailerlic'             => true,
		'retailerlicly_queryable' => true,
		'show_ui'                 => true,
		'query_var'               => true,
		'public'                  => true,
		'menu_icon'               => 'dashicons-groups',
		'rewrite'                 => true,
		'capability_type'         => 'post',
		'hierarchical'            => false,
		'supports'                => array( 'title', 'editor', 'thumbnail' ),
	);
	register_post_type( 'retailer', $args );
}
//add_image_size( 'retailer-logo-thumb', 70, 70, array('center', 'top') );
add_image_size( 'retailer-logo-thumb', 105, 105, false );

/* This is for Events */
add_action( 'init', 'event_register_function' );
function event_register_function() {
	$labels = array(
		'name'               => _x( 'Events', 'post type general name' ),
		'singular_name'      => _x( 'Events', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'Events item' ),
		'add_new_item'       => __( 'Add New Events' ),
		'edit_item'          => __( 'Edit Events Item' ),
		'new_item'           => __( 'New Events Item' ),
		'view_item'          => __( 'View Events Item' ),
		'search_items'       => __( 'Search Events' ),
		'not_found'          => __( 'Nothing found' ),
		'not_found_in_trash' => __( 'Nothing found in Trash' ),
		'parent_item_colon'  => '',
	);
	$args   = array(
		'labels'               => $labels,
		'eventlic'             => true,
		'eventlicly_queryable' => true,
		'show_ui'              => true,
		'query_var'            => true,
		'public'               => true,
		'menu_icon'            => get_bloginfo( 'template_directory' ) . '/images/Events.png',
		'rewrite'              => true,
		'capability_type'      => 'post',
		'hierarchical'         => false,
		'supports'             => array( 'title', 'thumbnail' ),
	);
	register_post_type( 'event', $args );
}
add_image_size( 'event-thumb', 60, 60, array( 'center', 'top' ) );
add_image_size( 'event-thumb-full', 1300, 1300 );

/* This is for Authenticity
register_taxonomy('authenticity_category', 'authenticity',array("hierarchical" => true,"label" => "Category","singular_label" => "Category",'update_count_callback' => '_update_post_term_count','query_var' => true,'rewrite' => array( 'slug' => 'authenticity_category', 'with_front' => false ),'adlic' => true,'show_ui' => true,'show_tagcloud' => true,'_builtin' => true,'show_in_nav_menus' => true));
add_action('init', 'authenticity_register_function');
function authenticity_register_function(){
	$labels = array(
		'name' => _x('Authenticity', 'post type general name'),
		'singular_name' => _x('Authenticity', 'post type singular name'),
		'add_new' => _x('Add New', 'Authenticity item'),
		'add_new_item' => __('Add New Authenticity'),
		'edit_item' => __('Edit Authenticity Item'),
		'new_item' => __('New Authenticity Item'),
		'view_item' => __('View Authenticity Item'),
		'search_items' => __('Search Authenticity'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'authenticitylic' => true,
		'authenticitylicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'public' => true,
		//'menu_icon' => get_bloginfo('template_directory').'/images/Authenticity.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array('title', 'editor', 'thumbnail'),
		'taxonomies' => array('authenticity_category')
	);
register_post_type( 'authenticity' , $args );
}
add_image_size( 'authenticity-thumb', 400, 400, array('center', 'top') );
*/
/* This is for Ternding
register_taxonomy('ternding_category', 'ternding',array("hierarchical" => true,"label" => "Category","singular_label" => "Category",'update_count_callback' => '_update_post_term_count','query_var' => true,'rewrite' => array( 'slug' => 'ternding_category', 'with_front' => false ),'adlic' => true,'show_ui' => true,'show_tagcloud' => true,'_builtin' => true,'show_in_nav_menus' => true));
add_action('init', 'ternding_register_function');
function ternding_register_function(){
	$labels = array(
		'name' => _x('Ternding', 'post type general name'),
		'singular_name' => _x('Ternding', 'post type singular name'),
		'add_new' => _x('Add New', 'Ternding item'),
		'add_new_item' => __('Add New Ternding'),
		'edit_item' => __('Edit Ternding Item'),
		'new_item' => __('New Ternding Item'),
		'view_item' => __('View Ternding Item'),
		'search_items' => __('Search Ternding'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'terndinglic' => true,
		'terndinglicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'public' => true,
		//'menu_icon' => get_bloginfo('url').'/images/Ternding.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array('title', 'editor', 'thumbnail'),
		'taxonomies' => array('ternding_category')
	);
register_post_type( 'ternding' , $args );
}
add_image_size( 'ternding-thumb', 60, 60, array('center', 'top') );
*/
/* This is for TV */
add_action( 'init', 'tv_register_function' );
function tv_register_function() {
	$labels = array(
		'name'               => _x( 'TV', 'post type general name' ),
		'singular_name'      => _x( 'TV', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'TV item' ),
		'add_new_item'       => __( 'Add New TV' ),
		'edit_item'          => __( 'Edit TV Item' ),
		'new_item'           => __( 'New TV Item' ),
		'view_item'          => __( 'View TV Item' ),
		'search_items'       => __( 'Search TV' ),
		'not_found'          => __( 'Nothing found' ),
		'not_found_in_trash' => __( 'Nothing found in Trash' ),
		'parent_item_colon'  => '',
	);
	$args   = array(
		'labels'            => $labels,
		'tvlic'             => true,
		'tvlicly_queryable' => true,
		'show_ui'           => true,
		'query_var'         => true,
		'public'            => true,
		//'menu_icon' => plugin_dir_url( __FILE__ ) .'/images/TV.png',
		'rewrite'           => true,
		'capability_type'   => 'post',
		'hierarchical'      => false,
		'supports'          => array( 'title' ),
		'taxonomies'        => array( 'tv_category' ),
	);
	register_post_type( 'tv', $args );
}

/* This is for Designer
register_taxonomy('designer_category', 'designer',array("hierarchical" => true,"label" => "Category","singular_label" => "Category",'update_count_callback' => '_update_post_term_count','query_var' => true,'rewrite' => array( 'slug' => 'designer_category', 'with_front' => false ),'adlic' => true,'show_ui' => true,'show_tagcloud' => true,'_builtin' => true,'show_in_nav_menus' => true));add_action('init', 'tv_register_function');
function designer_register_function(){
	$labels = array(
		'name' => _x('Designer', 'post type general name'),
		'singular_name' => _x('Designer', 'post type singular name'),
		'add_new' => _x('Add New', 'Designer item'),
		'add_new_item' => __('Add New Designer'),
		'edit_item' => __('Edit Designer Item'),
		'new_item' => __('New Designer Item'),
		'view_item' => __('View Designer Item'),
		'search_items' => __('Search Designer'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'designerlic' => true,
		'designerlicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'public' => true,
		//'menu_icon' => plugin_dir_url( __FILE__ ) .'/images/Designer.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array('title'),
		'taxonomies' => array('designer_category')
	);
register_post_type( 'designer' , $args );
}
*/
/* This is for Look Book */
add_action( 'init', 'look_book_register_function' );
function look_book_register_function() {
	$labels = array(
		'name'               => _x( 'Look Book', 'post type general name' ),
		'singular_name'      => _x( 'Look Book', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'Look Book item' ),
		'add_new_item'       => __( 'Add New Look Book' ),
		'edit_item'          => __( 'Edit Look Book Item' ),
		'new_item'           => __( 'New Look Book Item' ),
		'view_item'          => __( 'View Look Book Item' ),
		'search_items'       => __( 'Search Look Book' ),
		'not_found'          => __( 'Nothing found' ),
		'not_found_in_trash' => __( 'Nothing found in Trash' ),
		'parent_item_colon'  => '',
	);
	$args   = array(
		'labels'                   => $labels,
		'look_booklic'             => true,
		'look_booklicly_queryable' => true,
		'show_ui'                  => true,
		'query_var'                => true,
		'public'                   => true,
		//'menu_icon' => plugin_dir_url( __FILE__ ) .'/images/Look Book.png',
		'rewrite'                  => true,
		'capability_type'          => 'post',
		'hierarchical'             => false,
		'supports'                 => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'               => array( 'look_book_category' ),
	);
	register_post_type( 'look_book', $args );
}
/*add_image_size( 'look_book-thumb', 310, 200, array('center', 'top') );*/
add_image_size( 'look_book-thumb', 310, 9999, false );
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentytwelve_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Twelve 1.0
 */
function custom_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback':
		case 'trackback':
			// Display trackbacks differently than normal comments.
			?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)' ), '<span class="edit-link">', '</span>' ); ?></p>
			<?php
			break;
		default:
			// Proceed with normal comments.
			global $post;
			?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-meta comment-author vcard">
				<?php
					//echo get_avatar( $comment, 44 );
				?>
				<div class="comment-text">
				<p class="meta">
				<?php
					printf(
						'<cite><b class="fn">%1$s</b> %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span>' . __( ' - ' ) . '</span>' : ''
					);
				?>
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<?php _e( 'Your comment is awaiting moderation.' ); ?>
				<?php endif; ?>
					<section class="comment-content comment">
						<?php comment_text(); ?>
						<?php edit_comment_link( __( 'Edit' ), '<strong class="edit-link">', '</strong>' ); ?>
					</section><!-- .comment-content -->
						<?php
						comment_reply_link(
							array_merge(
								$args, array(
									'reply_text' => __( 'Reply' ),
									'after'      => ' <span></span>',
									'depth'      => $depth,
									'max_depth'  => $args['max_depth'],
								)
							)
						);
						?>
						<?php
							printf(
								'<time datetime="%2$s">%3$s</time>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s' ), get_comment_date(), get_comment_time() )
							);
						?>
						<div style="clear: both;"></div>
					</p>
				</div>
			</div><!-- .comment-meta -->
		</article><!-- #comment-## -->
			<?php
			break;
	endswitch; // end comment_type check
}

function processURL( $url ) {
	$ch = curl_init();
	curl_setopt_array(
		$ch, array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => 2,
		)
	);

	$result = curl_exec( $ch );
	curl_close( $ch );
	return $result;
}

function api_curl_connect( $api_url ) {
	$connection_c = curl_init(); // initializing
	curl_setopt( $connection_c, CURLOPT_URL, $api_url ); // API URL to connect
	curl_setopt( $connection_c, CURLOPT_RETURNTRANSFER, 1 ); // return the result, do not print
	curl_setopt( $connection_c, CURLOPT_TIMEOUT, 20 );
	$json_return = curl_exec( $connection_c ); // connect and get json data
	curl_close( $connection_c ); // close connection
	return json_decode( $json_return ); // decode and return
}


function jc_nav_menu_items( $items, $args ) {
	if ( is_user_logged_in() ) {
		if ( $args->theme_location == 'accnav' ) {
			$items .= '<li id="menu-item-logout" class="menu-item menu-item-logout"><a href="' . wp_logout_url( home_url() ) . '">' . __( 'Logout' ) . '</a></li>';
		}
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', 'jc_nav_menu_items', 10, 2 );

/**
 * Adds a meta box to the POST editing screen
 */
function post_custom_meta() {
	add_meta_box( 'post_meta', __( 'Assign Product' ), 'post_meta_callback', 'post', 'side', 'high' );
}
//add_action( 'add_meta_boxes', 'post_custom_meta' );

/**
 * Outputs the content of the meta box
 */
function post_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'post_nonce' );
	$post_stored_meta = get_post_meta( $post->ID, 'pro_assign', true );
	?>
	<p class="label">
		<label for="meta-text" class="row-title" style="13px !important"><?php _e( 'Select Products from the list below' ); ?></label>
	</p>

	<p>
		<select name="pro_assign[]" multiple="multiple" style="width: 100%;">
			<?php
			$the_query = new WP_Query(
				array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				)
			);
			?>
			<?php
				// Doc Loop
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) :
					$the_query->the_post();
					?>
						<option value="<?php echo get_the_ID(); ?>"
						<?php
						if ( isset( $post_stored_meta ) ) {
							$post_stored_meta_array = json_decode( $post_stored_meta );
							if ( ! empty( $post_stored_meta_array ) ) {
								if ( in_array( get_the_ID(), $post_stored_meta_array ) ) {
									echo 'selected="selected"';
								}
							}
						}
						?>
						>
						<?php the_title(); ?></option>';
					<?php
					endwhile;
			} else {
				echo 'No Product found.';
			}
				wp_reset_postdata();
			?>
		</select>
	</p>

	<?php
}

/**
 * Saves the custom meta input
 */
function post_meta_save( $post_id ) {

	// Checks save status
	$is_autosave    = wp_is_post_autosave( $post_id );
	$is_revision    = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST['post_nonce'] ) && wp_verify_nonce( $_POST['post_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false';

	// Exits script depending on save status
	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}

	// Checks for input and sanitizes/saves if needed
	if ( isset( $_POST['pro_assign'] ) ) {
		$subs = json_encode( $_POST['pro_assign'] );
		update_post_meta( $post_id, 'pro_assign', $subs );
	}

}
//add_action( 'save_post', 'post_meta_save' );


/**
 * Retrieves the thumbnail from a youtube or vimeo video
 * @param - $src: the url of the "player"
 * @return - string
 *
**/
function get_video_thumbnail( $src ) {
	$url_pieces = explode( '/', $src );

	if ( $url_pieces[2] == 'player.vimeo.com' ) { // If Vimeo
		$id        = $url_pieces[4];
		$hash      = unserialize( file_get_contents( 'http://vimeo.com/api/v2/video/' . $id . '.php' ) );
		$thumbnail = $hash[0]['thumbnail_large'];
	} elseif ( $url_pieces[2] == 'www.youtube.com' ) { // If Youtube
		$extract_id = explode( '?', $url_pieces[4] );
		$id         = $extract_id[0];
		$thumbnail  = 'http://img.youtube.com/vi/' . $id . '/mqdefault.jpg';
	}
	return $thumbnail;
}
function get_vimeoid( $url ) {
	$regex = '~
		# Match Vimeo link and embed code
		(?:<iframe [^>]*src=")?         # If iframe match up to first quote of src
		(?:                             # Group vimeo url
				https?:\/\/             # Either http or https
				(?:[\w]+\.)*            # Optional subdomains
				vimeo\.com              # Match vimeo.com
				(?:[\/\w]*\/videos?)?   # Optional video sub directory this handles groups links also
				\/                      # Slash before Id
				([0-9]+)                # $1: VIDEO_ID is numeric
				[^\s]*                  # Not a space
		)                               # End group
		"?                              # Match end quote if part of src
		(?:[^>]*></iframe>)?            # Match the end of the iframe
		(?:<p>.*</p>)?                  # Match any title information stuff
		~ix';

	preg_match( $regex, $url, $matches );

	return $matches[1];
}

function get_the_slug() {

	global $post;

	if ( is_single() || is_page() ) {
		return $post->post_name;
	} else {
		return '';
	}

}

add_action( 'wp_ajax_contentRefresh', 'contentRefresh' );

function contentRefresh() {
	if ( isset( $_REQUEST['page_no'] ) ) {
		$page = $_REQUEST['page_no'];
	} else {
		$page = 1;
	}
	global $user_ID;
	if ( empty( $_REQUEST['user_id'] ) ) {
		if ( ! empty( $_REQUEST['gallery_category'] ) && $_REQUEST['gallery_category'] != 'NULL' ) {
			$args = array(
				'post_type'      => 'gallery',
				'posts_per_page' => 10,
				'paged'          => $page,
				'post_status'    => 'publish',
				//'author' => 1,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'meta_query'     => array(
					array(
						'key'     => 'style_no',
						'value'   => trim( $_REQUEST['search_style_no'] ),
						'type'    => 'CHAR',
						'compare' => 'LIKE',
					),
					array( 'key' => '_thumbnail_id' ),
				),
				'tax_query'      => array(
					array(
						'taxonomy' => 'gallery_category',
						'field'    => 'id',
						'terms'    => array( $_REQUEST['gallery_category'] ),
						'operator' => 'IN',
					),
				),
			);
			if ( ! empty( $_REQUEST['search_style_no'] ) ) {
				$args = array(
					'post_type'      => 'gallery',
					'posts_per_page' => 10,
					'paged'          => $page,
					'post_status'    => 'publish',
					//'author' => 1,
					'orderby'        => 'date',
					'order'          => 'DESC',
					'meta_query'     => array(
						array(
							'key'     => 'style_no',
							'value'   => trim( $_REQUEST['search_style_no'] ),
							'type'    => 'CHAR',
							'compare' => 'LIKE',
						),
						array( 'key' => '_thumbnail_id' ),
					),
					'tax_query'      => array(
						array(
							'taxonomy' => 'gallery_category',
							'field'    => 'id',
							'terms'    => array( $_REQUEST['gallery_category'] ),
							'operator' => 'IN',
						),
					),
				);
			} else {
				$args = array(
					'post_type'      => 'gallery',
					'posts_per_page' => 10,
					'paged'          => $page,
					'post_status'    => 'publish',
					//'author' => 1,
					'meta_query'     => array( array( 'key' => '_thumbnail_id' ) ),
					'orderby'        => 'date',
					'order'          => 'DESC',
					'tax_query'      => array(
						array(
							'taxonomy' => 'gallery_category',
							'field'    => 'id',
							'terms'    => array( $_REQUEST['gallery_category'] ),
							'operator' => 'IN',
						),
					),
				);
			}
		} else {
			if ( ! empty( $_REQUEST['search_style_no'] ) ) {
				$args = array(
					'post_type'      => 'gallery',
					'posts_per_page' => 10,
					'paged'          => $page,
					'post_status'    => 'publish',
					//'author' => 1,
					'meta_query'     => array(
						array(
							'key'     => 'style_no',
							'value'   => trim( $_REQUEST['search_style_no'] ),
							'type'    => 'CHAR',
							'compare' => 'LIKE',
						),
						array( 'key' => '_thumbnail_id' ),
					),
					'orderby'        => 'date',
					'order'          => 'DESC',
				);
			} else {
				$args = array(
					'post_type'      => 'gallery',
					'posts_per_page' => 10,
					'paged'          => $page,
					'post_status'    => 'publish',
					//'author' => 1,
					'meta_query'     => array( array( 'key' => '_thumbnail_id' ) ),
					'orderby'        => 'date',
					'order'          => 'DESC',
				);
			}
		}
	} else {
		$args = array(
			'post_type'      => 'gallery',
			'posts_per_page' => 10,
			'paged'          => $page,
			'post_status'    => 'publish',
			'author'         => $user_ID,
			'meta_query'     => array( array( 'key' => '_thumbnail_id' ) ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
	}
	//print_r($args); wp_die();
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			?>
	<div class="metro-tile">
		<a onclick="ImageSelector('<?php the_post_thumbnail_url( 'full' ); ?>');" href="javascript:void(0);">
			<?php the_post_thumbnail( 'pro-thumbnail' ); ?>
			<span class="style_no"><?php echo get_the_title(); ?></span>
		</a>
			<?php
			if ( ! empty( $_REQUEST['user_id'] ) ) {
				?>
				<a href="javascript:void(0)" onclick="galleryDelete(<?php echo get_the_ID(); ?>)" class="metro-tile-delete"><i class="fa fa-trash"></i></a><?php } ?>
	</div>
			<?php
	endwhile;
		?>
	<div style="clear: both;"></div>
	<div id="custom_pagination">
		<?php
		$found_posts = $the_query->found_posts;
		if ( $found_posts > 1 ) {
			$pages = round( $found_posts / 10 );
			?>
			<ul>
				<?php for ( $i = 1;$i <= $pages;$i++ ) { ?>
					<li><a
					<?php
					if ( $i == $page ) {
						?>
						 class="active"
						 <?php
					} else {
						?>
						  onclick="pagination(<?php echo $i; ?>)";<?php } ?> href="javascript:void(0);"><?php echo $i; ?></a></li>
				<?php } ?>
			</ul>
		<?php } ?>
	</div>
		<?php
	} else {
		echo '<p>No images found.</p>';
	}
	wp_reset_postdata();
	wp_die();
}


add_action( 'wp_ajax_galleryDelete', 'galleryDelete' );

function galleryDelete() {
	$gid = $_REQUEST['gid'];
	wp_delete_post( $gid );
}


add_action( 'wp_ajax_ImgUpload', 'ImgUpload' );

function ImgUpload() {
	global $user_ID;
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		$name   = 'Gallery Image at ' . date( 'Y-m-d : H:i:sa' );
		$post   = array(
			'post_title'   => $name,
			'post_type'    => 'gallery',
			'post_status'  => 'publish',
			'post_content' => '',
			'post_author'  => $user_ID,
		);
		$new_ad = wp_insert_post( $post );

		require_once ABSPATH . 'wp-admin' . '/includes/image.php';
		require_once ABSPATH . 'wp-admin' . '/includes/file.php';
		require_once ABSPATH . 'wp-admin' . '/includes/media.php';

		$image = $_FILES['multiUpload'];
		if ( $image['size'] ) {
			if ( preg_match( '/(jpg|jpeg|png|gif)$/', $image['type'] ) ) {
				$override   = array( 'test_form' => false );
				$file       = wp_handle_upload( $image, $override );
				$attachment = array(
					'post_title'     => $image['name'],
					'post_content'   => '',
					'post_type'      => 'attachment',
					'post_mime_type' => $image['type'],
					'guid'           => $file['url'],
				);
				$attach_id  = wp_insert_attachment( $attachment, $file['file'] );
				wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $file['file'] ) );
				set_post_thumbnail( $new_ad, $attach_id );
				echo( $_POST['index'] );
			} else {
				wp_die( 'No image was uploaded.' );
			}
		}
	}
	wp_die();
}

add_action( 'wp_ajax_ImgPopContent', 'ImgPopContent' );

function ImgPopContent() {
	if ( $_REQUEST['type'] == 'Stock' ) {
		?>
		<div class="section group">
			<div class="col span_1_of_2">
				<form class="nav_search style_no_search" method="GET" action="">
					<input style="border: 1px solid #d5d6d8; padding: 7px;" type="text" name="search_style_no" placeholder="Search by Style Number">
					<input type="submit" value="">
				</form>
			</div>
			<div class="col span_1_of_2">
				<select name="gallery_category" class="tags">
					<option value="NULL">Select</option>
					<?php
					$taxonomy  = 'gallery_category';
					$tax_terms = get_terms(
						$taxonomy, array(
							'hide_empty' => 0,
							'orderby'    => 'slug',
						)
					);
					?>
					<?php
					foreach ( $tax_terms as $tax_term ) {
						echo '<option value="' . $tax_term->term_id . '">' . $tax_term->name . '</option>';
					}
					?>
				</select>
			</div>
		</div>
		<br />
		<div class="section group">
			<div class="col span_12_of_12">
				<div class="imageContent">
					<?php
					//$paged = get_query_var('paged') ? get_query_var('paged') : 1;
					global $user_ID;
					$args      = array(
						'post_type'      => 'gallery',
						'posts_per_page' => 10,
						'post_status'    => 'publish',
						'author'         => 1,
						'meta_query'     => array( array( 'key' => '_thumbnail_id' ) ),
						'orderby'        => 'date',
						'order'          => 'DESC',
					);
					$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) :
						while ( $the_query->have_posts() ) :
							$the_query->the_post();
							?>
							<div class="metro-tile">
								<a onclick="ImageSelector('<?php the_post_thumbnail_url( 'full' ); ?>');" href="javascript:void(0);">
									<?php the_post_thumbnail( 'pro-thumbnail' ); ?>
									<span class="style_no"><?php echo get_the_title(); ?></span>
								</a>
								<?php
								if ( $_GET['search_type'] == 'images_lib' ) {
									?>
									<a href="javascript:void(0)" onclick="galleryDelete(<?php echo get_the_ID(); ?>)" class="metro-tile-delete"><i class="fa fa-trash"></i></a><?php } ?>
							</div>
							<?php
						endwhile;
						?>
						<div style="clear: both;"></div>
						<div id="custom_pagination">
							<?php
							$found_posts = $the_query->found_posts;
							if ( $found_posts > 1 ) {
								$pages = round( $found_posts / 10 );
								?>
								<ul>
									<?php for ( $i = 1;$i <= $pages;$i++ ) { ?>
										<li><a
										<?php
										if ( $i == 1 ) {
											?>
											 class="active"
											 <?php
										} else {
											?>
											  onclick="pagination(<?php echo $i; ?>)";<?php } ?> href="javascript:void(0);"><?php echo $i; ?></a></li>
									<?php } ?>
								</ul>
							<?php } ?>
						</div>
						<?php
					endif;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
		<?php
	}
	if ( $_REQUEST['type'] == 'Mine' ) {
		?>
		<form id="uploadFile" enctype="multipart/form-data">
			<p><input type="file" name="multiUpload" id="multiUpload" /></p>
			<input type="hidden" name="action" value="ImgUpload"/>
			<p><input type="submit" name="submitHandler" id="submitHandler" value="Upload" class="buttonUpload" /></p>
		</form>
		<div class="progressBar">
			<div class="status"></div>
		</div>
		<div class="section group">
			<div class="col span_12_of_12">
				<div class="imageContent">
					<?php
					global $user_ID;
					$args      = array(
						'post_type'      => 'gallery',
						'posts_per_page' => 10,
						'post_status'    => 'publish',
						'author'         => $user_ID,
						'meta_query'     => array( array( 'key' => '_thumbnail_id' ) ),
						'orderby'        => 'date',
						'order'          => 'DESC',
					);
					$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) :
						while ( $the_query->have_posts() ) :
							$the_query->the_post();
							?>
							<div class="metro-tile">
								<a onclick="ImageSelector('<?php the_post_thumbnail_url( 'full' ); ?>');" href="javascript:void(0);">
									<?php the_post_thumbnail( 'pro-thumbnail' ); ?>
									<span class="style_no"><?php echo get_the_title(); ?></span>
								</a>
								<a href="javascript:void(0);" onclick="galleryDelete(<?php echo get_the_ID(); ?>)" class="metro-tile-delete"><i class="fa fa-trash"></i></a>
							</div>
							<?php
						endwhile;
						?>
						<div style="clear: both;"></div>
						<div id="custom_pagination">
							<?php
							$found_posts = $the_query->found_posts;
							if ( $found_posts > 1 ) {
								$pages = round( $found_posts / 10 );
								?>
								<ul>
									<?php for ( $i = 1;$i <= $pages;$i++ ) { ?>
										<li><a
										<?php
										if ( $i == 1 ) {
											?>
											 class="active"
											 <?php
										} else {
											?>
											  onclick="pagination(<?php echo $i; ?>)";<?php } ?> href="javascript:void(0);"><?php echo $i; ?></a></li>
									<?php } ?>
								</ul>
							<?php } ?>
						</div>
						<?php
					endif;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
		<?php global $user_ID; ?>
		<script type="text/javascript">
			jQuery('#uploadFile').submit(function(event) {
				event.preventDefault();
				jQuery('#Loader').show();
				jQuery.ajax({
					url: MyAjax.ajaxurl,
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					type: 'POST',
					success: function (response) {
						var formData = {
							'action': 'contentRefresh',
							'user_id': <?php echo $user_ID; ?>
						};
						jQuery.ajax({
							url: MyAjax.ajaxurl,
							data: formData,
							type: 'POST',
							success: function (response) {
								jQuery(".imageContent").html(response);
								jQuery('#Loader').hide();
							}
						});
					}
				});
			});
		</script>
		<?php
	}
	if ( $_REQUEST['type'] == 'Instagram' ) {
		global $user_ID;
		$instagram_access_token = get_user_meta( $user_ID, 'instagram_access_token', true );
		if ( ! empty( $instagram_access_token ) ) {
			$return = api_curl_connect( 'https://api.instagram.com/v1/users/self/media/recent?access_token=' . $instagram_access_token );
			//print_r($return);
			if ( ! empty( $return->data ) ) {
				?>
				<div class="section group">
				<div class="col span_12_of_12">
				<div class="imageContent">
				<?php
				foreach ( $return->data as $post ) {
					?>
					<div class="metro-tile">
						<a onclick="ImageSelector('<?php echo $post->images->standard_resolution->url; ?>');" href="javascript:void(0);">
							<img src="<?php echo $post->images->low_resolution->url; ?>" />
						</a>
					</div>
					<?php
				}
				?>
				</div>
				</div>
				</div>
				<?php
			} else {
				echo '<p>No images found.</p>';
			}
		} else {
			echo '<p>Instagram is not connected yet.</p>';
		}
	}
	if ( $_REQUEST['type'] == 'Pinterest' ) {
		global $user_ID;
		$pinterest_access_token = get_user_meta( $user_ID, 'pinterest_access_token', true );
		if ( ! empty( $pinterest_access_token ) ) {
			$boards = api_curl_connect( 'https://api.pinterest.com/v1/me/boards/?access_token=' . $pinterest_access_token . '&fields=id%2Cname' );
			if ( ! empty( $boards->data ) ) {
				?>
				<div class="section group">
					<div class="col span_6_of_12">
						<label for="select_board">Select Board</label>
						<select name="select_board" id="select_board">
							<option value="NULL">Select Board</option>
							<?php
							foreach ( $boards->data as $board ) {
								echo '<option value="' . $board->id . '">' . $board->name . '</option>';
							}
							?>
						</select>
					</div>
				</div>
				<?php
			}
			$return = api_curl_connect( 'https://api.pinterest.com/v1/me/pins/?access_token=' . $pinterest_access_token . '&fields=image%2Cnote' );
			//print_r($return);
			if ( ! empty( $return->data ) ) {
				?>
			<div class="section group">
				<div class="col span_12_of_12">
					<div class="imageContent">
						<?php
						foreach ( $return->data as $post ) {
							?>
							<div class="metro-tile">
								<a onclick="ImageSelector('<?php echo $post->image->original->url; ?>');" href="javascript:void(0);">
									<img src="<?php echo $post->image->original->url; ?>"/>
									<span class="style_no"><?php echo $post->note; ?></span>
								</a>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
			<script>
				jQuery('select[name="select_board"]').on('change', function() {
					var select_board = jQuery('select[name="select_board"]').val();
					if(select_board != 'NULL') {
						jQuery('#Loader').show();
						var formData = {
							'action': 'PinBoardContent',
							'select_board': select_board
						};
						jQuery.ajax({
							url: MyAjax.ajaxurl,
							data: formData,
							type: 'POST',
							success: function (response) {
								jQuery(".imageContent").html(response);
								jQuery('#Loader').hide();
							}
						});
					}
				});
			</script>
				<?php
			} else {
				echo '<p>No images found.</p>';
			}
		} else {
			echo '<p>Pinterest is not connected yet.</p>';
		}
	}
	wp_die();
}

add_action( 'wp_ajax_PinBoardContent', 'PinBoardContent' );

function PinBoardContent() {
	$board = $_REQUEST['select_board'];
	global $user_ID;
	$pinterest_access_token = get_user_meta( $user_ID, 'pinterest_access_token', true );
	$board                  = api_curl_connect( 'https://api.pinterest.com/v1/boards/' . $board . '/pins/?access_token=' . $pinterest_access_token . '&fields=id%2Curl%2Cnote%2Cimage' );
	if ( ! empty( $board->data ) ) {
		foreach ( $board->data as $post ) {
			?>
			<div class="metro-tile">
				<a onclick="ImageSelector('<?php echo $post->image->original->url; ?>');" href="javascript:void(0);">
					<img src="<?php echo $post->image->original->url; ?>"/>
					<span class="style_no"><?php echo $post->note; ?></span>
				</a>
			</div>
			<?php
		}
	} else {
		echo '<p>No images found.</p>';
	}
	wp_die();
}

add_action( 'wp_ajax_fancyboxImageSelector', 'fancyboxImageSelector' );

function fancyboxImageSelector() {
	?>
	<div class="section group">
		<div class="col span_12_of_12">
			<div class="ImageMenu">
				<ul>
					<li><a onclick="ImgPopContent('Stock');" href="javascript:void(0);">Stock</a></li>
					<li><a onclick="ImgPopContent('Mine');" href="javascript:void(0);">Mine</a></li>
					<li><a onclick="ImgPopContent('Instagram');" href="javascript:void(0);">Instagram</a></li>
					<li><a onclick="ImgPopContent('Pinterest');" href="javascript:void(0);">Pinterest</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="ImgPopContent">
		<div class="section group">
			<div class="col span_1_of_2">
				<form class="nav_search style_no_search" method="GET" action="">
					<input style="border: 1px solid #d5d6d8; padding: 7px;" type="text" name="search_style_no" placeholder="Search by Style Number">
					<input type="submit" value="">
				</form>
			</div>
			<div class="col span_1_of_2">
				<select name="gallery_category" class="tags">
					<option value="NULL">Select</option>
					<?php
					$taxonomy  = 'gallery_category';
					$tax_terms = get_terms(
						$taxonomy, array(
							'hide_empty' => 0,
							'orderby'    => 'slug',
						)
					);
					?>
					<?php
					foreach ( $tax_terms as $tax_term ) {
						echo '<option value="' . $tax_term->term_id . '">' . $tax_term->name . '</option>';
					}
					?>
				</select>
			</div>
		</div>
		<br />
		<div class="section group">
			<div class="col span_12_of_12">
				<div class="imageContent">
					<?php
					//$paged = get_query_var('paged') ? get_query_var('paged') : 1;
					global $user_ID;
					$args      = array(
						'post_type'      => 'gallery',
						'posts_per_page' => 10,
						'post_status'    => 'publish',
						'author'         => 1,
						'meta_query'     => array( array( 'key' => '_thumbnail_id' ) ),
						'orderby'        => 'date',
						'order'          => 'DESC',
					);
					$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) :
						while ( $the_query->have_posts() ) :
							$the_query->the_post();
							?>
					<div class="metro-tile">
						<a onclick="ImageSelector('<?php the_post_thumbnail_url( 'full' ); ?>');" href="javascript:void(0);">
							<?php the_post_thumbnail( 'pro-thumbnail' ); ?>
							<span class="style_no"><?php echo get_the_title(); ?></span>
						</a>
					</div>
							<?php
				endwhile;
						?>
				<div style="clear: both;"></div>
				<div id="custom_pagination">
						<?php
						$found_posts = $the_query->found_posts;
						if ( $found_posts > 1 ) {
							$pages = round( $found_posts / 10 );
							?>
						<ul>
							<?php for ( $i = 1;$i <= $pages;$i++ ) { ?>
								<li><a
								<?php
								if ( $i == 1 ) {
									?>
									 class="active"
									 <?php
								} else {
									?>
									  onclick="pagination(<?php echo $i; ?>)";<?php } ?> href="javascript:void(0);"><?php echo $i; ?></a></li>
							<?php } ?>
						</ul>
						<?php } ?>
				</div>
						<?php
				endif;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="section group">
		<div style="text-align: right;" class="col span_12_of_12">
			<a class="custom_button" href="javascript:void(0);" onclick="CancleImgSelect();">Cancle</a>
		</div>
	</div>
<script>
	function CancleImgSelect() {
		jQuery('#Loader').show();
		jQuery('.ImageSelectWrap').hide();
		jQuery('.socialMediaPostContent').show();
		jQuery('#Loader').hide();
	}
	function ImgPopContent(type) {
		jQuery('#Loader').show();
		var formData = {
			'action': 'ImgPopContent',
			'type': type
		};
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			data: formData,
			type: 'POST',
			success: function (response) {
				jQuery(".ImgPopContent").html(response);
				jQuery('#Loader').hide();
			}
		});
	}
	jQuery('select.tags').on('change', function() {
		var gallery_category = jQuery('select[name="gallery_category"]').val();
		jQuery('#Loader').show();
		var formData = {
			'action': 'contentRefresh',
			'gallery_category': gallery_category
		};
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			data: formData,
			type: 'POST',
			success: function (response) {
				jQuery(".imageContent").html(response);
				jQuery('#Loader').hide();
			}
		});
	});
	jQuery('.style_no_search').submit(function(event) {
		event.preventDefault();
		var search_style_no = jQuery('input[name="search_style_no"]').val();
		var gallery_category = jQuery('select[name="gallery_category"]').val();
		jQuery('#Loader').show();
		var formData = {
			'action': 'contentRefresh',
			'search_style_no': search_style_no,
			'gallery_category': gallery_category
		};
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			data: formData,
			type: 'POST',
			success: function (response) {
				jQuery(".imageContent").html(response);
				jQuery('#Loader').hide();
			}
		});
	});
	function pagination(page) {
		var search_style_no = jQuery('input[name="search_style_no"]').val();
		var gallery_category = jQuery('select[name="gallery_category"]').val();
		jQuery('#Loader').show();
		var formData = {
			'action': 'contentRefresh',
			'search_style_no': search_style_no,
			'gallery_category': gallery_category,
			'page_no': page
		};
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			data: formData,
			type: 'POST',
			success: function (response) {
				jQuery(".imageContent").html(response);
				jQuery('#Loader').hide();
				jQuery('.fancybox-inner').animate({
					scrollTop: jQuery(".imageContent").offset().top},
				'slow');
			}
		});
	}
	function galleryDelete(gid) {
		jQuery('#Loader').show();
		var formData = {
			'action': 'galleryDelete',
			'gid': gid
		};
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			data: formData,
			type: 'POST',
			success: function (response) {
				var formData = {
					'action': 'contentRefresh',
					'user_id': <?php echo $user_ID; ?>
				};
				jQuery.ajax({
					url: MyAjax.ajaxurl,
					data: formData,
					type: 'POST',
					success: function (response) {
						jQuery(".imageContent").html(response);
						jQuery('#Loader').hide();
					}
				});
			}
		});
	}
</script>
	<?php
	wp_die();
}

add_action( 'wp_ajax_dropPopupEditDate', 'dropPopupEditDate' );

function dropPopupEditDate() {
	$eventId = $_REQUEST['EditID'];
	$tz      = get_option( 'timezone_string' );
	$tz      = get_option( 'timezone_string' );
	global $user_ID;
	$user_timezone = get_user_meta( $user_ID, 'user_timezone', true );
	if ( empty( $user_timezone ) ) {
		$user_timezone = $tz;
	}
	$event_date = $_REQUEST['event_date'];
	/*$schedule_date = new DateTime($event_date, new DateTimeZone($user_timezone) );
	$schedule_date->setTimeZone(new DateTimeZone($tz));
	$event_date =  $schedule_date->format('Y-m-d');*/
	update_post_meta( $eventId, 'event_date', $event_date );
}

add_action( 'wp_ajax_edit_data', 'edit_data' );

function edit_data() {
	$eventId = $_REQUEST['eventId'];
	$tz      = get_option( 'timezone_string' );
	$tz      = get_option( 'timezone_string' );
	global $user_ID;
	$user_timezone = get_user_meta( $user_ID, 'user_timezone', true );
	if ( empty( $user_timezone ) ) {
		$user_timezone = $tz;
	}
	$event_date    = $_REQUEST['event_date'];
	$schedule_date = new DateTime( $event_date, new DateTimeZone( $user_timezone ) );
	//$schedule_date->setTimeZone(new DateTimeZone($tz));
	$event_date = $schedule_date->format( 'Y-m-d' );

	$event_time    = $_REQUEST['event_time'];
	$schedule_time = new DateTime( $event_time, new DateTimeZone( $user_timezone ) );
	$schedule_time->setTimeZone( new DateTimeZone( $tz ) );
	$event_time = $schedule_time->format( 'g:i A' );

	if ( $_REQUEST['submitValue'] == 'delete' ) {
		wp_delete_post( $eventId );
		echo 0;
	} else {
		$post = array(
			'ID'           => $eventId,
			'post_title'   => $_REQUEST['event_name'],
			'post_content' => $_REQUEST['event_content'],
		);
		wp_update_post( $post );
		update_post_meta( $eventId, 'event_date', $event_date );
		update_post_meta( $eventId, 'event_time', $event_time );
		update_post_meta( $eventId, 'event_image', $_REQUEST['event_image'] );
		//update_post_meta($eventId, 'pinboard', $_REQUEST['Pinboard']);

		if ( get_post_meta( $eventId, 'type', true ) == 'email' ) {
			update_post_meta( $eventId, 'subjectLine', $_REQUEST['subjectLine'] );
			update_post_meta( $eventId, 'previewtext', $_REQUEST['previewtext'] );
			update_post_meta( $eventId, 'fromName', $_REQUEST['fromName'] );
			update_post_meta( $eventId, 'fromEmail', $_REQUEST['fromEmail'] );
			update_post_meta( $eventId, 'mailchimpList', $_REQUEST['mailchimpList'] );
			if ( $_REQUEST['type2'] == 'email_template' ) {
				update_post_meta( $eventId, 'templateStyle', $_REQUEST['gjs-styles'] );
				update_post_meta( $eventId, 'templateHtml', $_REQUEST['gjs-html'] );
			}
		}

		if ( get_post_meta( $eventId, 'type', true ) == 'pinterest' ) {
			update_post_meta( $eventId, 'pinboard', $_REQUEST['Pinboard'] );
		}
		print_r( $_REQUEST );
		exit();

		echo 1;
	}
	/*$event = array(
		'Title' => $_REQUEST['event_name'],
		'Description' => $_REQUEST['event_content'],
		'StartAt' => $_REQUEST['event_date'],
		'EndAt' => $_REQUEST['event_date'],
		'Icon' => get_post_meta($eventId, 'type', true)
	);
	echo json_encode($event);*/
	wp_die();
}

add_action( 'wp_ajax_save_data', 'save_data' );

function save_data() {
	global $user_ID;
	$tz            = get_option( 'timezone_string' );
	$user_timezone = get_user_meta( $user_ID, 'user_timezone', true );
	if ( empty( $user_timezone ) ) {
		$user_timezone = $tz;
	}
	$event_date    = $_REQUEST['event_date'];
	$schedule_date = new DateTime( $event_date, new DateTimeZone( $user_timezone ) );
	//$schedule_date->setTimeZone(new DateTimeZone($tz));
	$event_date = $schedule_date->format( 'Y-m-d' );

	$event_time    = $_REQUEST['event_time'];
	$schedule_time = new DateTime( $event_time, new DateTimeZone( $user_timezone ) );
	$schedule_time->setTimeZone( new DateTimeZone( $tz ) );
	$event_time = $schedule_time->format( 'g:i A' );

	$post         = array(
		'post_title'   => $_REQUEST['event_name'],
		'post_content' => $_REQUEST['event_content'],
		'post_type'    => 'cal_post_event',
		'post_status'  => 'publish',
		'post_author'  => $user_ID,
	);
	$new_cal_post = wp_insert_post( $post );
	add_post_meta( $new_cal_post, 'event_date', $event_date );
	add_post_meta( $new_cal_post, 'event_time', $event_time );
	add_post_meta( $new_cal_post, 'type', $_REQUEST['type'] );
	add_post_meta( $new_cal_post, 'event_image', $_REQUEST['event_image'] );

	if ( $_REQUEST['type'] == 'email' ) {
		add_post_meta( $new_cal_post, 'subjectLine', $_REQUEST['subjectLine'] );
		add_post_meta( $new_cal_post, 'previewtext', $_REQUEST['previewtext'] );
		add_post_meta( $new_cal_post, 'fromName', $_REQUEST['fromName'] );
		add_post_meta( $new_cal_post, 'fromEmail', $_REQUEST['fromEmail'] );
		add_post_meta( $new_cal_post, 'mailchimpList', $_REQUEST['mailchimpList'] );
		if ( $_REQUEST['type2'] == 'email_template' ) {
			add_post_meta( $new_cal_post, 'templateStyle', $_REQUEST['gjs-styles'] );
			add_post_meta( $new_cal_post, 'templateHtml', $_REQUEST['gjs-html'] );
		}
	}
	if ( $_REQUEST['type'] == 'pinterest' ) {
		add_post_meta( $new_cal_post, 'pinboard', $_REQUEST['Pinboard'] );
	}

	$event = array(
		'Title'       => $_REQUEST['event_name'],
		'Description' => $_REQUEST['event_content'],
		'StartAt'     => $_REQUEST['event_date'],
		'EndAt'       => $_REQUEST['event_date'],
		'Icon'        => $_REQUEST['type'],
	);
	echo json_encode( $event );
	wp_die();
}

add_action( 'wp_ajax_getEventType', 'getEventType' );

function getEventType() {
	$eventId   = $_REQUEST['EventID'];
	echo $type = get_post_meta( $eventId, 'type', true );
	wp_die();
}

add_action( 'wp_ajax_pre_post_data', 'pre_post_data' );

function pre_post_data() {
	$args      = array(
		'p'              => $_POST['pre_post_id'],
		'post_type'      => 'social_posts',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) :
		$pre_post_data = array();
		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			$pre_post_data = array(
				'title'   => get_the_title(),
				'content' => get_the_content(),
			);
		endwhile;
		echo json_encode( $pre_post_data );
	endif;
	wp_reset_postdata();
	wp_die();
}

add_action( 'wp_ajax_dropPopupEdit', 'dropPopupEdit' );

function dropPopupEdit() {
	global $user_ID;
	$tz      = get_option( 'timezone_string' );
	$eventId = $_REQUEST['EditID'];
	$title   = get_the_title( $eventId );

	$user_timezone = get_user_meta( $user_ID, 'user_timezone', true );
	if ( empty( $user_timezone ) ) {
		$user_timezone = $tz;
	}

	$event_date    = get_post_meta( $eventId, 'event_date', true );
	$schedule_date = new DateTime( $event_date, new DateTimeZone( $tz ) );
	//$schedule_date->setTimeZone(new DateTimeZone($user_timezone));
	$event_date = $schedule_date->format( 'Y-m-d' );

	$event_time    = get_post_meta( $eventId, 'event_time', true );
	$schedule_time = new DateTime( $event_time, new DateTimeZone( $tz ) );
	$schedule_time->setTimeZone( new DateTimeZone( $user_timezone ) );
	$event_time = $schedule_time->format( 'g:i A' );

	$event_image = get_post_meta( $eventId, 'event_image', true );
	$post_type   = get_post_type( $eventId );
	if ( ! empty( $event_image ) ) {
		$image_text = 'Change Image';
	} else {
		$image_text = 'Add Image';
	}
	$post_object = get_post( $eventId );
	$description = $post_object->post_content;
	$type        = get_post_meta( $eventId, 'type', true );
	if ( $type == 'email' ) {
		$icon = '<i class="fa fa-envelope" aria-hidden="true"></i>';
	}
	if ( $type == 'facebook' ) {
		$icon = '<i class="fa fa-facebook" aria-hidden="true"></i>';
	}
	if ( $type == 'twitter' ) {
		$icon = '<i class="fa fa-twitter" aria-hidden="true"></i>';
	}
	if ( $type == 'pinterest' ) {
		$icon = '<i class="fa fa-pinterest" aria-hidden="true"></i>';
	}
	if ( $type == 'instagram' ) {
		$icon = '<i class="fa fa-instagram" aria-hidden="true"></i>';
	}
	if ( $type == 'sms' ) {
		$icon = '<i class="fa fa-comment" aria-hidden="true"></i>';
	}
	if ( $type == 'vip' ) {
		$icon = '<i class="fa fa-eercast" aria-hidden="true"></i>';
	}
	if ( $type == 'content_ideas' ) {
		$icon = '<i class="fa fa-eercast" aria-hidden="true"></i>';
	}
	?>
	<div class="socialMediaPostContent">
		<form>
			<?php
			$args      = array(
				'post_type'      => 'social_posts',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) :
				?>
				<div>
					<label>Select Predefined Posts</label>
					<select class="form-control pre_post">
						<option>Select</option>
						<?php
						while ( $the_query->have_posts() ) :
							$the_query->the_post();
							?>
							<option value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<?php
			endif;
			wp_reset_postdata();
			?>
			<br/>
			<div>
				<label>Title</label>
				<br/>
				<input type="text" class="form-control" name="event_name" value="<?php echo $title; ?>" ng-model="formData.event_name" ng-init="formData.event_name='<?php echo $title; ?>'" required="required">
				<input type="hidden" name="eventId" ng-model="formData.eventId" ng-init="formData.eventId='<?php echo $eventId; ?>'">
			</div>
			<div>
				<?php
				if ( $type == 'pinterest' ) {
					global $user_ID;
					$Pinboard               = get_post_meta( $eventId, 'pinboard', true );
					$pinterest_access_token = get_user_meta( $user_ID, 'pinterest_access_token', true );
					if ( ! empty( $pinterest_access_token ) ) {
						$boards = api_curl_connect( 'https://api.pinterest.com/v1/me/boards/?access_token=' . $pinterest_access_token . '&fields=id%2Cname' );
						if ( ! empty( $boards->data ) ) {
							?>
							<div class="section group">
								<div class="col span_6_of_12">
									<label for="select_board">Select Board</label>
									<br/>
									<select required id="select_board">
										<option value="NULL">Select Board</option>
										<?php
										foreach ( $boards->data as $board ) {
											if ( $Pinboard = $board->id ) {
												echo '<option selected="selected" value="' . $board->id . '">' . $board->name . '</option>';
											} else {
												echo '<option value="' . $board->id . '">' . $board->name . '</option>';
											}
										}
										?>
									</select>
								</div>
							</div>
							<input type="hidden" name="Pinboard" ng-model="formData.Pinboard" ng-init="formData.Pinboard='<?php echo $Pinboard; ?>'" value="<?php echo $Pinboard; ?>">
							<script>
								jQuery('#select_board').on('change', function (e) {
									var Pinboard = jQuery('#select_board').val();
									jQuery('input[name="Pinboard"]').attr('ng-init', "formData.Pinboard='"+Pinboard+"'");
									jQuery('input[name="Pinboard"]').val(Pinboard);
								});
							</script>
							<?php
						}
					}
				}
				?>
			</div>
			<?php if ( $type == 'email' ) { ?>
				<br/>
				<div>
					<label>Subject line</label>
					<br/>
					<?php $subjectLine = get_post_meta( $eventId, 'subjectLine', true ); ?>
					<input type="text" class="form-control" name="subjectLine" placeholder="Subject line" ng-model="formData.subjectLine" value="<?php echo $subjectLine; ?>" ng-init="formData.subjectLine='<?php echo $subjectLine; ?>'" required="required">
				</div>
				<br/>
				<div>
					<label>Preview Text</label>
					<br/>
					<?php $previewtext = get_post_meta( $eventId, 'previewtext', true ); ?>
					<textarea class="form-control" name="previewtext" ng-model="formData.previewtext" required="required" ng-init="formData.previewtext='<?php echo $previewtext; ?>'"><?php echo $previewtext; ?></textarea>
				</div>
				<br/>
				<div>
					<label>From Name</label>
					<br/>
					<?php $fromName = get_post_meta( $eventId, 'fromName', true ); ?>
					<input type="text" class="form-control" name="fromName" placeholder="From Name" ng-model="formData.fromName" required="required" value="<?php echo $fromName; ?>" ng-init="formData.subjectLine='<?php echo $fromName; ?>'">
				</div>
				<br/>
				<div>
					<label>From Email Address</label>
					<br/>
					<?php $fromEmail = get_post_meta( $eventId, 'fromEmail', true ); ?>
					<input type="text" class="form-control" name="fromEmail" placeholder="From Email Address" ng-model="formData.fromEmail" required="required" value="<?php echo $fromEmail; ?>" ng-init="formData.fromEmail='<?php echo $fromEmail; ?>'">
				</div>
				<?php $api_key = get_user_meta( $user_ID, 'mailchimp_access_token', true ); ?>
				<?php if ( ! empty( $api_key ) ) { ?>
				<div>
					<label>Select Mailchimp List</label>
					<br/>
					<?php
					$data = array(
						'fields' => 'lists',
						'count'  => '20',
					);

					$url    = get_user_meta( $user_ID, 'mailchimp_endpoint', true ) . '/3.0/lists/';
					$result = json_decode( mailchimp_curl_connect( $url, 'GET', $api_key, $data ) );

					if ( ! empty( $result->lists ) ) {
						echo '<select name="mailchimpList">';
						foreach ( $result->lists as $list ) {
							echo '<option value="' . $list->id . '">' . $list->name . ' (' . $list->stats->member_count . ')</option>';
						}
											echo '<option value="istilist">iSTiLiST</option>';
						echo '</select>';
					} elseif ( is_int( $result->status ) ) {
						echo '<strong>' . $result->title . ':</strong> ' . $result->detail;
					}
					?>
				</div>
				<?php } ?>
			<?php } ?>
			<div>
				<div class="socialPost">
					<div class="postHeader">
						<div class="postTypeIconContainer">
							<?php echo $icon; ?>
						</div>
						<div class="postScheduleContainer">
							<span>Posted on</span>
							<?php $start = $_REQUEST['start']; ?>
							<input autocomplete="off" type="text" name="event_date" id="datepicker" class="form-control" value="<?php echo $event_date; ?>" >
							<input autocomplete="off" type="text" name="event_time" id="timepicker" class="form-control" value="<?php echo $event_time; ?>"  />
							<input type="hidden" name="event_date" value="<?php echo $event_date; ?>" id="datepicker_hidden" ng-model="formData.event_date" ng-init="formData.event_date='<?php echo $event_date; ?>'" >
							<input type="hidden" name="event_time" value="<?php echo $event_time; ?>" id="timepicker_hidden" ng-model="formData.event_time" ng-init="formData.event_time='<?php echo $event_time; ?>'" >
						</div>
						<div class="clearDiv"></div>
					</div>
					<div class="postMessageContainer">
						<?php if ( $type == 'email' || $_REQUEST['type'] == 'email' ) { ?>
							<!--<textarea class="form-control emailEditor" name="event_content"><?php /*echo $description; */ ?></textarea>-->
							<div style="margin: 20px 0; text-align: center;">
								<a class="custom_button email_editor" href="javascript:void(0);">Open Email Editor</a>
							</div>
							<script>
								jQuery(document).ready(function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList+"&editID=<?php echo $eventId; ?>");
								});
								jQuery('input[name="event_name"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList+"&editID=<?php echo $eventId; ?>");
										jQuery('.email_editor').show();
										jQuery('.fancybox-inner').animate({
												scrollTop: jQuery(".email_editor").offset().top},
											'slow');
									} else {
										jQuery('.email_editor').hide();
									}
								});
								jQuery('input[name="subjectLine"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList+"&editID=<?php echo $eventId; ?>");
										jQuery('.email_editor').show();
										jQuery('.fancybox-inner').animate({
												scrollTop: jQuery(".email_editor").offset().top},
											'slow');
									} else {
										jQuery('.email_editor').hide();
									}
								});
								jQuery('textarea[name="previewtext"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList+"&editID=<?php echo $eventId; ?>");
										jQuery('.email_editor').show();
										jQuery('.fancybox-inner').animate({
												scrollTop: jQuery(".email_editor").offset().top},
											'slow');
									} else {
										jQuery('.email_editor').hide();
									}
								});
								jQuery('input[name="fromName"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList+"&editID=<?php echo $eventId; ?>");
										jQuery('.email_editor').show();
									} else {
										jQuery('.email_editor').hide();
									}
								});
								jQuery('input[name="fromEmail"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList+"&editID=<?php echo $eventId; ?>");
										jQuery('.email_editor').show();
										jQuery('.fancybox-inner').animate({
												scrollTop: jQuery(".email_editor").offset().top},
											'slow');
									} else {
										jQuery('.email_editor').hide();
									}
								});
								jQuery('input[name="event_date"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList+"&editID=<?php echo $eventId; ?>");
										jQuery('.email_editor').show();
										jQuery('.fancybox-inner').animate({
												scrollTop: jQuery(".email_editor").offset().top},
											'slow');
									} else {
										jQuery('.email_editor').hide();
									}
								});
								jQuery('input[name="event_time"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList+"&editID=<?php echo $eventId; ?>");
										alert(mailchimpList);
										jQuery('.email_editor').show();
										jQuery('.fancybox-inner').animate({
												scrollTop: jQuery(".email_editor").offset().top},
											'slow');
									} else {
										jQuery('.email_editor').hide();
									}
								});
							</script>
						<?php } else { ?>
							<textarea required="required" style="height: 280px;" class="form-control" name="event_content" ng-model="formData.event_content"  ng-init="formData.event_content='<?php echo $description; ?>'"><?php echo $description; ?></textarea>
						<?php } ?>
					</div>
				</div>
			</div>
			<p></p>
			<p></p>
			<div class="section group">
				<div class="col span_3_of_12">
					<?php if ( $post_type != 'global_event' ) { ?>
					<input class="btn-primary" type="submit" ng-click="processFormDelete()" value="Delete">
					<?php } ?>
				</div>
				<div class="col span_2_of_12" style="text-align: center;">
					<img class="attachedImage" width="50" height="50" style="vertical-align: middle;" src="<?php echo $event_image; ?>" />
				</div>
				<div class="col span_4_of_12" style="text-align: center;">
					<?php
					if ( $type == 'email' || $type == 'sms' ) {
					} else {
						?>
					<a class="custom_button attachedImageButton" href="javascript:void(0);" ng-click="fancyboxImageSelector()"><?php echo $image_text; ?></a>
					<input type="hidden" id="ImageSelector" name="ImageSelector" value="<?php echo $event_image; ?>" ng-model="formData.ImageSelector" ng-init="formData.ImageSelector='<?php echo $event_image; ?>'" />
					<?php } ?>
				</div>
				<div class="col span_3_of_12">
					<div style="text-align: right;">
						<?php if ( $post_type != 'global_event' && $type != 'email' ) { ?>
						<input class="btn-primary" ng-click="processFormEdit()" type="submit" value="Update">
						<?php } ?>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="ImageSelectWrap"></div>
	<script>
		jQuery(document).ready(function() {
			jQuery('#datepicker').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			jQuery('#timepicker').timepicker({
				timeFormat: 'h:i A',
				useSelect: false,
				step: 5
			});
			jQuery('#datepicker').change(function() {
				var date = jQuery(this).val();
				jQuery('#datepicker_hidden').attr('ng-init', "formData.event_date='"+date+"'");
				jQuery('#datepicker_hidden').val(date);
			});
			jQuery('#timepicker').change(function() {
				var time = jQuery(this).val();
				jQuery('#timepicker_hidden').attr('ng-init', "formData.event_time='"+time+"'");
				jQuery('#timepicker_hidden').val(time)
			});
			<?php if ( $type == 'email' ) { ?>
			tinymce.init({
				selector: 'textarea.emailEditor',
				height: 500,
				theme: 'modern',
				plugins: [
					'advlist autolink lists link image charmap print preview hr anchor pagebreak',
					'searchreplace wordcount visualblocks fullscreen',
					'insertdatetime nonbreaking save table contextmenu directionality',
					'paste textcolor colorpicker textpattern imagetools codesample toc help emoticons hr'
				],
				menubar: "format table",
				toolbar1: 'print preview searchreplace | spellchecker a11ycheck | undo redo | insert | bullist numlist outdent indent |  visualblocks fullscreen',
				toolbar2: 'styleselect | fontselect | fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | forecolor backcolor | removeformat | addimage',
				content_css: [
					'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
					'//www.tinymce.com/css/codepen.min.css'
				],
				setup: function (editor) {
					editor.addButton('addimage', {
						text: '',
						icon: 'addImage',
						onclick: function () {
							angular.element(document.getElementById('content')).scope().fancyboxImageSelector();
							//editor.insertContent('&nbsp;<b>It\'s my button!</b>&nbsp;');
						}
					});
				},
			});
			<?php } ?>
		});
	</script>
	<?php
	wp_die();
}

add_action( 'wp_ajax_dropPopup', 'dropPopup' );

function dropPopup() {
	global $user_ID;
	if ( $_REQUEST['type'] == 'email' ) {
		$title       = 'Email';
		$description = 'Edit Email Template';
		$icon        = '<i class="fa fa-envelope" aria-hidden="true"></i>';
	}
	if ( $_REQUEST['type'] == 'facebook' ) {
		$title       = 'Facebook Post';
		$description = 'Add an description that will appear on Facebook when your this posts.';
		$icon        = '<i class="fa fa-facebook" aria-hidden="true"></i>';
	}
	if ( $_REQUEST['type'] == 'twitter' ) {
		$title       = 'Twitter Post';
		$description = 'Add an description that will appear on Twitter when your this posts.';
		$icon        = '<i class="fa fa-twitter" aria-hidden="true"></i>';
	}
	if ( $_REQUEST['type'] == 'pinterest' ) {
		$title       = 'Pinterest Post';
		$description = 'Add an description that will appear on Pinterest when your this posts.';
		$icon        = '<i class="fa fa-pinterest" aria-hidden="true"></i>';
	}
	if ( $_REQUEST['type'] == 'instagram' ) {
		$title       = 'Instagram Post';
		$description = 'Add an description that will appear on Instagram when your this posts.';
		$icon        = '<i class="fa fa-instagram" aria-hidden="true"></i>';
	}
	if ( $_REQUEST['type'] == 'sms' ) {
		$title       = 'Text Message';
		$description = 'Text Message.';
		$icon        = '<i class="fa fa-comment" aria-hidden="true"></i>';
	}
	if ( $_REQUEST['type'] == 'vip' ) {
		$title       = 'VIP Event';
		$description = 'Add an description that will appear on VIP Event when your this posts.';
		$icon        = '<i class="fa fa-eercast" aria-hidden="true"></i>';
	}
	if ( $_REQUEST['type'] == 'content_ideas' ) {
		$title       = 'Content Ideas';
		$description = 'Add an description that will appear on Content Ideas when your this posts.';
		$icon        = '<i class="fa fa-eercast" aria-hidden="true"></i>';
	}
	?>
	<!--<div class="loading_icon" style="text-align: center; display: none;">
		<img src="<?php /*bloginfo('template_directory'); */ ?>/images/loading_spinner.gif"/>
	</div>-->
	<div class="socialMediaPostContent">
		<form ng-submit="processForm()">
			<?php
			$args      = array(
				'post_type'      => 'social_posts',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) :
				?>
				<div>
					<label>Select Predefined Posts</label>
					<select class="form-control pre_post">
						<option>Select</option>
						<?php
						while ( $the_query->have_posts() ) :
							$the_query->the_post();
							?>
							<option value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<?php
			endif;
			wp_reset_postdata();
			?>
			<br/>
			<div>
				<label>Title</label>
				<br/>
				<input type="text" class="form-control" name="event_name" placeholder="<?php echo $title; ?>" ng-model="formData.event_name" required="required" value="">
				<?php $type = $_REQUEST['type']; ?>
				<input type="hidden" name="type" ng-model="formData.type" ng-init="formData.type='<?php echo $type; ?>'">
			</div>
			<div>
			<?php
			if ( $_REQUEST['type'] == 'pinterest' ) {
				global $user_ID;
				$pinterest_access_token = get_user_meta( $user_ID, 'pinterest_access_token', true );
				if ( ! empty( $pinterest_access_token ) ) {
					$boards = api_curl_connect( 'https://api.pinterest.com/v1/me/boards/?access_token=' . $pinterest_access_token . '&fields=id%2Cname' );
					if ( ! empty( $boards->data ) ) {
						?>
						<div class="section group">
							<div class="col span_6_of_12">
								<label for="select_board">Select Board</label>
								<br/>
								<select id="select_board">
									<option required value="NULL">Select Board</option>
									<?php
									foreach ( $boards->data as $board ) {
										echo '<option value="' . $board->id . '">' . $board->name . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<input type="hidden" name="Pinboard" ng-model="formData.Pinboard" ng-init="formData.Pinboard=''" value="">
						<script>
							jQuery('#select_board').on('change', function (e) {
								var Pinboard = jQuery('#select_board').val();
								jQuery('input[name="Pinboard"]').attr('ng-init', "formData.Pinboard='"+Pinboard+"'");
								jQuery('input[name="Pinboard"]').val(Pinboard);
							});
						</script>
						<?php
					}
				}
			}
			?>
			</div>
			<?php if ( $_REQUEST['type'] == 'email' ) { ?>
				<br/>
				<div>
					<label>Subject line</label>
					<br/>
					<input type="text" class="form-control" name="subjectLine" placeholder="Subject line" ng-model="formData.subjectLine" required="required" value="">
				</div>
				<br/>
				<div>
					<label>Preview Text</label>
					<br/>
					<textarea class="form-control" name="previewtext" ng-model="formData.previewtext" required="required">Preview Text</textarea>
				</div>
				<br/>
				<div>
					<label>From Name</label>
					<br/>
					<input type="text" class="form-control" name="fromName" placeholder="From Name" ng-model="formData.fromName" required="required" value="">
				</div>
				<br/>
				<div>
					<label>From Email Address</label>
					<br/>
					<input type="text" class="form-control" name="fromEmail" placeholder="From Email Address" ng-model="formData.fromEmail" required="required" value="">
				</div>
				<?php $api_key = get_user_meta( $user_ID, 'mailchimp_access_token', true ); ?>
				<?php if ( ! empty( $api_key ) ) { ?>
				<div>
					<br/>
					<label>Select Mailchimp List</label>
					<br/>
					<?php
					$data = array(
						'fields' => 'lists',
						'count'  => '20',
					);

					$url    = get_user_meta( $user_ID, 'mailchimp_endpoint', true ) . '/3.0/lists/';
					$result = json_decode( mailchimp_curl_connect( $url, 'GET', $api_key, $data ) );

					if ( ! empty( $result->lists ) ) {
						echo '<select name="mailchimpList">';
						foreach ( $result->lists as $list ) {
							echo '<option value="' . $list->id . '">' . $list->name . ' (' . $list->stats->member_count . ')</option>';
						}
											echo '<option value="istilist">iSTiLiST</option>';
						echo '</select>';
					} elseif ( is_int( $result->status ) ) {
						echo '<strong>' . $result->title . ':</strong> ' . $result->detail;
					}
					?>
				</div>
				<?php } ?>
			<?php } ?>
			<div>
				<div class="socialPost">
					<div class="postHeader">
						<div class="postTypeIconContainer">
							<?php echo $icon; ?>
						</div>
						<div class="postScheduleContainer">
							<span>Posted on</span>
							<?php $event_date = $_REQUEST['start']; ?>
							<input autocomplete="off" type="text" name="event_date" id="datepicker" class="form-control" value="<?php echo $event_date; ?>" >
							<input autocomplete="off" type="text" name="event_time" id="timepicker" class="form-control" value=""  />
							<input type="hidden" name="event_date" value="<?php echo $event_date; ?>" id="datepicker_hidden" ng-model="formData.event_date" ng-init="formData.event_date='<?php echo $event_date; ?>'" >
							<input type="hidden" name="event_time" id="timepicker_hidden" ng-model="formData.event_time" ng-init="formData.event_time='aa'" >
						</div>
						<div class="clearDiv"></div>
					</div>
					<div class="postMessageContainer">
						<?php if ( $_REQUEST['type'] == 'email' ) { ?>
							<!--<textarea class="form-control emailEditor" name="event_content"><?php /*echo $description; */ ?></textarea>-->
							<div style="margin: 20px 0; text-align: center;">
								<a style="display: none;" class="custom_button email_editor" href="javascript:void(0);">Open Email Editor</a>
							</div>
							<script>
								jQuery('input[name="event_name"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList);
										jQuery('.email_editor').show();
										jQuery('.fancybox-inner').animate({
											scrollTop: jQuery(".email_editor").offset().top},
										'slow');
									} else {
										jQuery('.email_editor').hide();
									}
								});
								jQuery('input[name="subjectLine"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList);
										jQuery('.email_editor').show();
										jQuery('.fancybox-inner').animate({
											scrollTop: jQuery(".email_editor").offset().top},
										'slow');
									} else {
										jQuery('.email_editor').hide();
									}
								});
								jQuery('textarea[name="previewtext"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList);
										jQuery('.email_editor').show();
										jQuery('.fancybox-inner').animate({
											scrollTop: jQuery(".email_editor").offset().top},
										'slow');
									}
								});
								jQuery('input[name="fromName"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList);
										jQuery('.email_editor').show();
									} else {
										jQuery('.email_editor').hide();
									}
								});
								jQuery('input[name="fromEmail"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList);
										jQuery('.email_editor').show();
										jQuery('.fancybox-inner').animate({
											scrollTop: jQuery(".email_editor").offset().top},
										'slow');
									} else {
										jQuery('.email_editor').hide();
									}
								});
								jQuery('input[name="event_date"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList);
										jQuery('.email_editor').show();
										jQuery('.fancybox-inner').animate({
											scrollTop: jQuery(".email_editor").offset().top},
										'slow');
									} else {
										jQuery('.email_editor').hide();
									}
								});
								jQuery('input[name="event_time"]').on('change', function() {
									var event_name = jQuery('input[name="event_name"]').val();
									var subjectLine = jQuery('input[name="subjectLine"]').val();
									var previewtext = jQuery('textarea[name="previewtext"]').val();
									var fromName = jQuery('input[name="fromName"]').val();
									var fromEmail = jQuery('input[name="fromEmail"]').val();
									var event_date = jQuery('input[name="event_date"]').val();
									var event_time = jQuery('input[name="event_time"]').val();
									var mailchimpList = jQuery('select[name="mailchimpList"]').val();
									if(event_name != '' && subjectLine != '' && previewtext != '' && fromName != '' && fromEmail != '' && event_date != '' && event_time != '') {
										jQuery('.email_editor').attr('href', "<?php bloginfo( 'url' ); ?>/email-editor/?event_name="+event_name+"&subjectLine="+subjectLine+"&previewtext="+previewtext+"&fromName="+fromName+"&fromEmail="+fromEmail+"&event_date="+event_date+"&event_time="+event_time+"&mailchimpList="+mailchimpList);
										jQuery('.email_editor').show();
										jQuery('.fancybox-inner').animate({
											scrollTop: jQuery(".email_editor").offset().top},
										'slow');
									} else {
										jQuery('.email_editor').hide();
									}
								});
							</script>
						<?php } else { ?>
							<textarea required="required" style="height: 280px;" class="form-control" name="event_content" ng-model="formData.event_content" placeholder="<?php echo $description; ?>"></textarea>
						<?php } ?>
					</div>
					<div class=""></div>
				</div>
			</div>
			<p></p>
			<p></p>
			<div class="section group">
				<div class="col span_2_of_12" style="text-align: center;">
					<img class="attachedImage" width="50" height="50" style="vertical-align: middle;
					<?php
					if ( $_REQUEST['type'] == 'email' ) {
						?>
						 display: none;<?php } ?>" src="" />
				</div>
				<div class="col span_4_of_12">
					<?php
					if ( $_REQUEST['type'] == 'email' || $_REQUEST['type'] == 'sms' ) {
					} else {
						?>
					<a class="custom_button attachedImageButton" href="javascript:void(0);" ng-click="fancyboxImageSelector()">Add Image</a>
					<input type="hidden" id="ImageSelector" name="ImageSelector" value="" ng-model="formData.ImageSelector" ng-init="formData.ImageSelector=''" />
					<?php } ?>
				</div>
				<div class="col span_6_of_12">
					<?php if ( $_REQUEST['type'] != 'email' ) { ?>
					<div style="text-align: right;">
						<input class="btn-primary" type="submit" value="Submit">
					</div>
					<?php } ?>
				</div>
			</div>
		</form>
	</div>
	<div class="ImageSelectWrap"></div>
	<script>
		jQuery(document).ready(function() {
			jQuery('#datepicker').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			jQuery('#timepicker').timepicker({
				timeFormat: 'h:i A',
				stepMinute: 5
			});
			jQuery('#datepicker').change(function() {
				var date = jQuery(this).val();
				jQuery('#datepicker_hidden').attr('ng-init', "formData.event_date='"+date+"'");
				jQuery('#datepicker_hidden').val(date);
			});
			jQuery('#timepicker').change(function() {
				var time = jQuery(this).val();
				jQuery('#timepicker_hidden').attr('ng-init', "formData.event_time='"+time+"'");
				jQuery('#timepicker_hidden').val(time)
			});
			<?php if ( $_REQUEST['type'] == 'email' ) { ?>
			tinymce.init({
				selector: 'textarea.emailEditor',
				height: 500,
				theme: 'modern',
				plugins: [
					'advlist autolink lists link image charmap print preview hr anchor pagebreak',
					'searchreplace wordcount visualblocks fullscreen',
					'insertdatetime nonbreaking save table contextmenu directionality',
					'paste textcolor colorpicker textpattern imagetools codesample toc help emoticons hr'
				],
				menubar: "format table",
				toolbar1: 'print preview searchreplace | spellchecker a11ycheck | undo redo | insert | bullist numlist outdent indent |  visualblocks fullscreen',
				toolbar2: 'styleselect | fontselect | fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | forecolor backcolor | removeformat | addimage',
				content_css: [
					'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
					'//www.tinymce.com/css/codepen.min.css'
				],
				setup: function (editor) {
					editor.addButton('addimage', {
						text: '',
						icon: 'addImage',
						onclick: function () {
							angular.element(document.getElementById('content')).scope().fancyboxImageSelector();
							//editor.insertContent('&nbsp;<b>It\'s my button!</b>&nbsp;');
						}
					});
				},
			});
			<?php } ?>
		});
	</script>
	<?php
	wp_die();
}

add_action( 'wp_ajax_optOut', 'optOut' );

function optOut() {
	global $user_ID;
	$optOut = json_decode( get_user_meta( $user_ID, 'optOut', true ) );
	if ( ! empty( $optOut ) ) {
		array_push( $optOut, $_REQUEST['EditID'] );
	} else {
		$optOut = array();
		array_push( $optOut, $_REQUEST['EditID'] );
	}
	update_user_meta( $user_ID, 'optOut', json_encode( $optOut ) );
	echo 1;
	wp_die();
}

add_action( 'wp_ajax_globalEvents', 'globalEvents' );

function globalEvents() {
	global $user_ID;
	$tz            = get_option( 'timezone_string' );
	$user_timezone = get_user_meta( $user_ID, 'user_timezone', true );
	if ( empty( $user_timezone ) ) {
		$user_timezone = $tz;
	}

	$page_object = get_post( $_REQUEST['EditID'] );

	$event_date    = get_post_meta( $_REQUEST['EditID'], 'event_date', true );
	$schedule_date = new DateTime( $event_date, new DateTimeZone( $tz ) );
	//$schedule_date->setTimeZone(new DateTimeZone($user_timezone));
	$event_date    = $schedule_date->format( 'Y-m-d' );
	$event_time    = get_post_meta( $_REQUEST['EditID'], 'event_time', true );
	$schedule_time = new DateTime( $event_time, new DateTimeZone( $tz ) );
	$schedule_time->setTimeZone( new DateTimeZone( $user_timezone ) );
	$event_time = $schedule_time->format( 'h:i A' );
	$event      = array(
		'title'   => get_the_title( $_REQUEST['EditID'] ),
		'content' => $page_object->post_content,
		'date'    => $event_date,
		'time'    => $event_time,
	);
	echo json_encode( $event );
	wp_die();
}

add_action( 'wp_ajax_mcal_action', 'mcal_action' );

function mcal_action() {
	global $user_ID;
	$tz            = get_option( 'timezone_string' );
	$user_timezone = get_user_meta( $user_ID, 'user_timezone', true );
	if ( empty( $user_timezone ) ) {
		$user_timezone = $tz;
	}
	if ( ! empty( $_REQUEST['datatype'] ) ) {
		$args = array(
			'post_type'      => 'cal_post_event',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'author'         => $user_ID,
			'meta_query'     => array(
				array(
					'key'     => 'type',
					'value'   => $_REQUEST['datatype'],
					'type'    => 'CHAR',
					'compare' => 'LIKE',
				),
			),
		);

	} else {
		$args = array(
			'post_type'      => 'cal_post_event',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'author'         => $user_ID,
		);
	}
	$events    = array();
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			$event_date    = get_post_meta( get_the_ID(), 'event_date', true );
			$schedule_date = new DateTime( $event_date, new DateTimeZone( $tz ) );
			//$schedule_date->setTimeZone(new DateTimeZone($user_timezone));
			$event_date    = $schedule_date->format( 'Y-m-d' );
			$event_time    = get_post_meta( get_the_ID(), 'event_time', true );
			$schedule_time = new DateTime( $event_time, new DateTimeZone( $tz ) );
			$schedule_time->setTimeZone( new DateTimeZone( $user_timezone ) );
			$event_time1 = $schedule_time->format( 'H:i:s' );
			$event_time2 = $schedule_time->format( 'h:i A' );
			$title       = $event_time2 . ' ' . get_the_title();
			$event       = array(
				'Title'       => $title,
				'Description' => get_the_excerpt(),
				'StartAt'     => $event_date . 'T' . $event_time1,
				//'EndAt' => $event_date,
				'Icon'        => get_post_meta( get_the_ID(), 'type', true ),
				'ID'          => get_the_ID(),
			);
			array_push( $events, $event );
		endwhile;
	endif;
	$optOut     = json_decode( get_user_meta( $user_ID, 'optOut', true ) );
	$args1      = array(
		'post_type'      => 'global_event',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'post__not_in'   => $optOut,
	);
	$the_query1 = new WP_Query( $args1 );
	if ( $the_query1->have_posts() ) :
		while ( $the_query1->have_posts() ) :
			$the_query1->the_post();
			$event_date    = get_post_meta( get_the_ID(), 'event_date', true );
			$schedule_date = new DateTime( $event_date, new DateTimeZone( $tz ) );
			//$schedule_date->setTimeZone(new DateTimeZone($user_timezone));
			$event_date    = $schedule_date->format( 'Y-m-d' );
			$event_time    = get_post_meta( get_the_ID(), 'event_time', true );
			$schedule_time = new DateTime( $event_time, new DateTimeZone( $tz ) );
			$schedule_time->setTimeZone( new DateTimeZone( $user_timezone ) );
			$event_time1         = $schedule_time->format( 'H:i:s' );
			$event_time2         = $schedule_time->format( 'h:i A' );
			$title               = $event_time2 . ' ' . get_the_title();
			$class               = '';
			$globalShare         = 0;
			$globalSuggestions   = 0;
			$globalAnnouncements = 0;
			if ( has_term( 'global-share', 'global_event_category' ) ) {
				$class      .= ' global-share';
				$globalShare = 1;
			}
			if ( has_term( 'global-suggestions', 'global_event_category' ) ) {
				$class            .= ' global-suggestions';
				$globalSuggestions = 1;
			}
			if ( has_term( 'global-announcements', 'global_event_category' ) ) {
				$class              .= ' global-announcements';
				$globalAnnouncements = 1;
			}
			$event = array(
				'Title'               => $title,
				'Description'         => get_the_excerpt(),
				'StartAt'             => $event_date . 'T' . $event_time1,
				//'EndAt' => $event_date,
				'Icon'                => get_post_meta( get_the_ID(), 'type', true ),
				'ID'                  => get_the_ID(),
				'editable'            => 'false',
				'ExtraClass'          => $class,
				'globalShare'         => $globalShare,
				'globalSuggestions'   => $globalSuggestions,
				'globalAnnouncements' => $globalAnnouncements,
			);
			array_push( $events, $event );
		endwhile;
	endif;
	echo json_encode( $events );
	wp_die();
}

function mm_scripts_basic() {

		$directory = get_stylesheet_directory_uri();

		wp_enqueue_script( 'custom-angularjs', $directory . '/node_modules/angular/angular.min.js', array(), true, true );
		wp_enqueue_script( 'custom-calendar', $directory . '/node_modules/angular-ui-calendar/src/calendar.js', array( 'custom-angularjs' ), true, true );
		wp_enqueue_script( 'custom-script', $directory . '/js/custom-script.js', array( 'jquery', 'custom-angularjs' ), true, true );
		wp_enqueue_script( 'custom-fullcalendar', $directory . '/node_modules/fullcalendar/dist/fullcalendar.min.js', array( 'jquery', 'custom-moment', 'jquery-ui-core' ), true, true );
		wp_enqueue_script( 'custom-moment', $directory . '/node_modules/moment/min/moment.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'jquery-confirm', $directory . '/node_modules/jquery-confirm/dist/jquery-confirm.min.js', array(), true, true );
		wp_enqueue_script( 'jquery-ui-core', '', array( 'jquery' ), true, true );
		wp_enqueue_script( 'jquery-ui-datepicker', '', array( 'jquery', 'jquery-ui-core' ), true, true );
		wp_enqueue_script( 'jquery-ui-timepicker', $directory . '/js/jquery.timepicker.js', array( 'jquery', 'jquery-ui-core' ), true, true );
		wp_enqueue_script( 'jquery-ui-mouse', '', array( 'jquery-ui-core' ), true, true );
		wp_enqueue_script( 'jquery-ui-draggable', '', array( 'jquery-ui-core' ), true, true );
		wp_enqueue_script( 'jquery-datetimepicker', $directory . '/js/jquery.datetimepicker.js', array( 'jquery', 'jquery-ui-core' ), true, true );
		wp_enqueue_script( 'jquery-tinymce', 'https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=ktzixf62qqu05yekd7dcoi1mzg3lqf7bl08zwtuzeuf1loc4', array(), true, true );
		wp_enqueue_script( 'custom-grapes', $directory . '/node_modules/grapesjs/dist/grapes.min.js', array(), true, true );

		wp_enqueue_style( 'jquery-datetimepicker', $directory . '/css/jquery.datetimepicker.css', array(), true );
		wp_enqueue_style( 'custom_fullcalendar', $directory . '/node_modules/fullcalendar/dist/fullcalendar.min.css', array(), true );
		wp_enqueue_style( 'custom_socialMediaPost', $directory . '/css/socialMediaPost.css', array(), true );
		wp_enqueue_style( 'jquery_confirm', $directory . '/node_modules/jquery-confirm/css/jquery-confirm.css', array(), true );
		wp_enqueue_style( 'custom_grapes', $directory . '/node_modules/grapesjs/dist/css/grapes.min.css', array(), true );
		wp_enqueue_style( 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css', array(), true );
		wp_enqueue_style( 'jquery-timepicker', $directory . '/css/jquery.timepicker.css', array(), true );

		wp_localize_script( 'custom-script', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

}

add_action( 'wp_enqueue_scripts', 'mm_scripts_basic' );


function gallery_view_enqueue( $hook ) {
	if ( 'edit.php' != $hook ) {
		return;
	}

	/*wp_register_script('jquery-ui-custom-js', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js', array('jquery'));
	wp_enqueue_script('jquery-ui-custom-js');
	wp_enqueue_style( 'jquery-ui-custom-css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');*/

	/*wp_register_script('jquery-datatable-js', '//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js');
	wp_enque_script('jquery-datatable-js');
	wp_enque_style('jquery-datatable-css', '//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css');*/

	wp_register_script( 'galleryView_script', get_bloginfo( 'template_directory' ) . '/js/galleryViewscript.js', array( 'jquery' ) );
	wp_localize_script( 'galleryView_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_script( 'galleryView_script' );
}
add_action( 'admin_enqueue_scripts', 'gallery_view_enqueue' );


/* This is for Social Media Posts */
add_action( 'init', 'social_posts_register_function' );
function social_posts_register_function() {
	$labels = array(
		'name'               => _x( 'Social Media Posts', 'post type general name' ),
		'singular_name'      => _x( 'Social Media Posts', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'Social Media Posts item' ),
		'add_new_item'       => __( 'Add New Social Media Posts' ),
		'edit_item'          => __( 'Edit Social Media Posts Item' ),
		'new_item'           => __( 'New Social Media Posts Item' ),
		'view_item'          => __( 'View Social Media Posts Item' ),
		'search_items'       => __( 'Search Social Media Posts' ),
		'not_found'          => __( 'Nothing found' ),
		'not_found_in_trash' => __( 'Nothing found in Trash' ),
		'parent_item_colon'  => '',
	);
	$args   = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'query_var'          => true,
		//'menu_icon' => plugin_dir_url( __FILE__ ) .'/images/hgdfh.png',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'supports'           => array( 'title', 'editor' ),
	);
	register_post_type( 'social_posts', $args );
}
register_taxonomy(
	'global_event_category', 'global_even', array(
		'hierarchical'          => true,
		'label'                 => 'Category',
		'singular_label'        => 'Category',
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array(
			'slug'       => 'global_event_category',
			'with_front' => false,
		),
		'public'                => true,
		'show_ui'               => true,
		'show_tagcloud'         => true,
		'_builtin'              => true,
		'show_in_nav_menus'     => false,
	)
);
add_action( 'init', 'global_event_register_function' );
function global_event_register_function() {
	$labels = array(
		'name'               => _x( 'Global Event', 'post type general name' ),
		'singular_name'      => _x( 'Global Event', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'Global Event item' ),
		'add_new_item'       => __( 'Add New Global Event' ),
		'edit_item'          => __( 'Edit Global Event Item' ),
		'new_item'           => __( 'New Global Event Item' ),
		'view_item'          => __( 'View Global Event Item' ),
		'search_items'       => __( 'Search Global Event' ),
		'not_found'          => __( 'Nothing found' ),
		'not_found_in_trash' => __( 'Nothing found in Trash' ),
		'parent_item_colon'  => '',
	);
	$args   = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'query_var'          => true,
		'public'             => false,
		'menu_icon'          => 'dashicons-share-alt',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'supports'           => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'         => array( 'global_event_category' ),
	);
	register_post_type( 'global_event', $args );
}
add_action( 'admin_menu', 'csv_upload_global_event' );
function csv_upload_global_event() {
	add_submenu_page( 'edit.php?post_type=global_event', 'Import From CSV', 'Import From CSV', 'manage_options', 'admin_csv_import', 'admin_csv_import' );
}
function admin_csv_import() {
	require_once 'admin_csv_import.php';
}

function readCSV( $csvFile ) {
	$file_handle = fopen( $csvFile, 'r' );
	while ( ! feof( $file_handle ) ) {
		$line_of_text[] = fgetcsv( $file_handle, 1024 );
	}
	fclose( $file_handle );
	return $line_of_text;
}
function hide_publishing_actions() {
	$my_post_type = 'global_event';
	global $post;
	if ( $post->post_type == $my_post_type ) {
		echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>
            ';
	}
}
add_action( 'admin_head-post.php', 'hide_publishing_actions' );
add_action( 'admin_head-post-new.php', 'hide_publishing_actions' );

add_filter( 'manage_global_event_posts_columns', 'doctor_appointment_columns' );

add_action( 'manage_global_event_posts_custom_column', 'doctor_appointment_columns_with_value', 10, 2 );

function doctor_appointment_columns( $columns ) {

	$columns['type']      = 'Type';
	$columns['date_time'] = 'Date and Time';

	unset( $columns['date'] );

	return $columns;

}
function doctor_appointment_columns_with_value( $column, $post_id ) {
	global $post;

	switch ( $column ) {

		case 'type':
			$type = get_post_meta( $post_id, 'type', true );
			switch ( $type ) {
				case 'facebook':
					echo 'Facebook';
					break;
				case 'twitter':
					echo 'Twitter';
					break;
				case 'email':
					echo 'Email';
					break;
				case 'pinterest':
					echo 'Pinterest';
					break;
				case 'sms':
					echo 'Text Message';
				case 'vip':
					echo 'VIP Event';
					break;
				case 'content_ideas':
					echo 'Content Ideas';
					break;
				default:
					break;
			}
			break;

		case 'date_time':
			echo get_field( 'event_date', $post_id ) . ' ' . get_field( 'event_time', $post_id );
			break;

		default:
			break;
	}
}


add_filter( 'manage_gallery_posts_columns', 'tj_columns_gallery_head' );
add_action( 'manage_gallery_posts_custom_column', 'tj_columns_gallery_content', 10, 2 );

// ADD TWO NEW COLUMNS
function tj_columns_gallery_head( $defaults ) {
	$defaults['view'] = 'Show/Hide';
	unset( $defaults['date'] );
	$defaults['date'] = 'Date';
	//$defaults['sort'] = 'Sort';
	return $defaults;
}

function tj_columns_gallery_content( $column_name, $post_ID ) {
	if ( $column_name == 'view' ) {
		$view = get_post_meta( $post_ID, 'view', true );
		if ( empty( $view ) ) {
			$view = 0;
		}
		?>
		<!--<input type="hidden" name="featured_id" value="<?php /*echo $post_ID; */ ?>">-->
		<?php if ( $view != 1 ) { ?>
			<input class="View_<?php echo $post_ID; ?>" type="hidden" name="view" value="1">
			<img class="view_checkbox" data-PostId="<?php echo $post_ID; ?>" src="<?php bloginfo( 'template_directory' ); ?>/images/success.png">
		<?php } else { ?>
			<input class="View_<?php echo $post_ID; ?>" type="hidden" name="view" value="0">
			<img class="view_checkbox" data-PostId="<?php echo $post_ID; ?>" src="<?php bloginfo( 'template_directory' ); ?>/images/error.png">
		<?php } ?>
		<img class="ajax_loader ajax_loader_<?php echo $post_ID; ?>" src="<?php bloginfo( 'template_directory' ); ?>/images/ajax-loader.gif">
		<style>
			.ajax_loader {
				display: none;
			}
			.view_checkbox {
				cursor: pointer;
			}
		</style>
		<?php
	}
	/*if ($column_name == 'sort') {
		*/
	?>
	<!--
		<a href="javascript:void(0)" class="movedownlink" >
			<span class="glyphicon glyphicon-arrow-down"></span></a>
		--><?php
		/*    }*/
}

add_action( 'wp_ajax_reOrder', 'reOrder' );

function reOrder() {
	foreach ( $_POST['orderArray'] as $key => $data ) {
		update_post_meta( $key, 'order', (int) $data );
	}
	wp_die();
}

add_action( 'wp_ajax_galleryView', 'galleryView' );

function galleryView() {
	if ( $_POST['view'] == 1 ) {
		update_post_meta( $_POST['PostId'], 'view', 1 );
	}
	if ( $_POST['view'] == 0 ) {
		update_post_meta( $_POST['PostId'], 'view', 0 );
	}
	wp_die();
}

add_action( 'admin_menu', 'gallery_submenu' );
function gallery_submenu() {
	add_submenu_page( 'edit.php?post_type=gallery', 'Designer Order', 'Designer Order', 'manage_options', 'gallery-order', 'gallery_order_callback' );
}
function gallery_order_callback() {
	include_once TEMPLATEPATH . '/admin_gallery_order.php';
}

function mailchimp_curl_connect( $url, $request_type, $api_key, $data = array() ) {
	if ( $request_type == 'GET' ) {
		$url .= '?' . http_build_query( $data );
	}

	$mch     = curl_init();
	$headers = array(
		'Content-Type: application/json',
		'Authorization: Basic ' . base64_encode( 'user:' . $api_key ),
	);
	curl_setopt( $mch, CURLOPT_URL, $url );
	curl_setopt( $mch, CURLOPT_HTTPHEADER, $headers );
	//curl_setopt($mch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
	curl_setopt( $mch, CURLOPT_RETURNTRANSFER, true ); // do not echo the result, write it into variable
	curl_setopt( $mch, CURLOPT_CUSTOMREQUEST, $request_type ); // according to MailChimp API: POST/GET/PATCH/PUT/DELETE
	curl_setopt( $mch, CURLOPT_TIMEOUT, 10 );
	curl_setopt( $mch, CURLOPT_SSL_VERIFYPEER, false ); // certificate verification for TLS/SSL connection

	if ( $request_type != 'GET' ) {
		curl_setopt( $mch, CURLOPT_POST, true );
		curl_setopt( $mch, CURLOPT_POSTFIELDS, json_encode( $data ) ); // send data in json
	}

	return curl_exec( $mch );
}


add_action( 'admin_menu', 'text_price' );
function text_price() {
	add_menu_page( 'Twilio Text Price', 'Twilio Text Price', 'manage_options', 'twilio-text-price', 'twilio_text_price', 'dashicons-smartphone', 55 );
}
function twilio_text_price() {
	include_once TEMPLATEPATH . '/admin_twilio_text_price.php';
}
?>
