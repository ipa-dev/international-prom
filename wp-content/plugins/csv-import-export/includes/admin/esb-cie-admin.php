<?php

/**
 * Admin File
 * Handles to admin functionality & other functions
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Custom add admin menu page
 */
function esb_cie_admin_menu_pages() {

    add_menu_page( __( 'ESB CSV Import - Export', 'esbcie' ),  __( 'Import - Export', 'esbcie' ), 'manage_options', 'esb-cie-import-export-page', 'esb_cie_import_export_page' );
}

/**
 * Import - Export page
 */
function esb_cie_import_export_page(){
    
    include ESB_CIE_DIR . '/includes/admin/views/esb-cie-import-export-page.php';
}

/**
 * Generate Sample CSV
 */
function esb_cie_generate_sample_csv() {
    
    if( !empty( $_POST['esb_cie_download_sample_csv'] ) && !empty( $_POST['esb_cie_column_name'] ) ) {
        
        $columns = $_POST['esb_cie_column_name'];
        $file_name   = !empty( $_POST['esb_cie_csv_file_name'] ) ? $_POST['esb_cie_csv_file_name'] : 'sample';
        
        $exports = '';
        
        foreach ( $columns as $column ) {

            $exports .= '"'.$column.'",';
        }
        $exports .="\n";

        // Output to browser with appropriate mime type, you choose ;)
        header("Content-type: text/x-csv");
        header("Content-Disposition: attachment; filename=".$file_name.".csv");
        echo $exports;
        exit;
    }
}

/**
 * Import csv data and insert posts
 */
function esb_cie_import_post_type_csv() {
    
    if( !empty( $_POST['esb_cie_import_csv'] ) && !empty( $_POST['esb_cie_csv_post_type_name'] )
        && !empty( $_FILES['esb_cie_import_file'] ) ) {
        
        $post_type_name = $_POST['esb_cie_csv_post_type_name'];
        
        $row = $total_import_count = $total_update_count = $total_ignore_count = 0;
        $post_key_data = $post_taxonomies_data = array();
        if (($handle = fopen($_FILES['esb_cie_import_file']['tmp_name'], "r")) !== FALSE) {
        	
            
            while (($data = fgetcsv($handle, 1000, ",", '"', "\n")) !== FALSE) {
            	
                if( $row > 0 ) {
                    $num = count($data);
			
                    for ($c=0; $c < $num; $c++) {
                        
                        //String Conversation with UTF-8
                        $data[$c] = esb_cie_string_conversion( $data[$c] );
                        $pos = strpos( $post_key_data[$c], 'tax-' );
                        if ($pos !== false) { // check taxonomy
                            
                            $taxonomy = strstr( $post_key_data[$c], '-' );
                            $taxonomy = trim( $taxonomy, '-' );
                            
                            $post_taxonomies_data[$taxonomy] = $data[$c];
                            
                        } else {
                            switch ( $post_key_data[$c] ) {

                                case 'post_image':
                                                    $post_image = esb_cie_check_post_image( $data[$c] );
                                                    $temp_data[$post_key_data[$c]] = $post_image;
                                                    break;

                                case 'additional-images':
                                                    $add_images = array();
                                                    $add_images = esb_cie_check_additional_images( $data[$c] );
                                                    $temp_data_add_images = $add_images;
                                                    break;
                                case 'sizes':
                                                    $sizes = esb_cie_check_post_size( $data[$c] );
                                                    $temp_data_sizes = $sizes;
                                                    break;
                                case 'colors':
                                                    $colors = esb_cie_check_post_color( $data[$c] );
                                                    $temp_data_colors = $colors;
                                                    break;
                                case 'view':
                                                    //$view = esb_cie_check_post_view( $data[$c] );
                                                    $temp_data_view = $data[$c];
                                                    break;
                                case 'redirect-url':
                                                    //$url = esb_cie_check_post_url( $data[$c] );
                                                    $temp_data_url = $data[$c];
                                                    break;
                                case 'event_date':
                                                    $event_date = esb_cie_check_event_date( $data[$c] );
                                                    $temp_data_event_date = $event_date;
                                                    break;
                                case 'event_time':
                                                    $event_time = esb_cie_check_event_time( $data[$c] );
                                                    $temp_data_event_time = $event_time;
                                                    break;
                                case 'style_no':
                                                    $style_no = $data[$c];
                                                    $temp_data_style_no = $style_no;
                                                    break;

                                default:
                                                    $temp_data[$post_key_data[$c]] = $data[$c];
                                                    break;
                            }
                        }
                    }
                    $temp_data['post_type'] = $post_type_name;

                    /* Post Insert / Update / Ignore functionaly start */
                    if( isset( $_POST['esb_cie_import_choice'] ) ) {
                    
                        $current_post_data = $temp_data;
                        if( isset( $temp_data['post_image'] ) ) {
                            unset( $temp_data['post_image'] );
                        }
                        $current_post_id = 0;
                        
                        
                        
                        if( $_POST['esb_cie_import_choice'] == 'new' ) {
                            
                            
                            $current_post_id = wp_insert_post( $temp_data );
                           

                            if ($post_type_name == 'gallery') {
	                            add_post_meta($current_post_id, 'sizes', $temp_data_sizes);
	                            add_post_meta($current_post_id, 'colors', $temp_data_colors);
	                            add_post_meta($current_post_id, 'view', $temp_data_view);
	                            add_post_meta($current_post_id, 'redirect_url', $temp_data_url);
	                            update_field('product_images', $temp_data_add_images, $current_post_id);
	                            add_post_meta($current_post_id, 'style_no', $temp_data_style_no);
	                    }
	                    if ($post_type_name == 'global_event') {
	                            add_post_meta($current_post_id, 'event_date', $temp_data_event_date);
	                            add_post_meta($current_post_id, 'event_time', $temp_data_event_time);
	                    }

                            //add_post_meta($current_post_id, 'product_images', serialize($temp_data_add_images));
                            $total_import_count++;
                            
                        } else if( $_POST['esb_cie_import_choice'] == 'update' ) {
                            
                            $current_post_id = esb_cie_check_post_by_slug( $current_post_data['post_name'], $post_type_name );
                            if( !empty( $current_post_id ) ) {
                                
                                $temp_data['ID'] = $current_post_id;
                                wp_update_post( $temp_data );
                                if ($post_type_name == 'gallery') {
	                                update_post_meta($temp_data['ID'], 'sizes', $temp_data_sizes);
	                                update_post_meta($temp_data['ID'], 'colors', $temp_data_colors);
	                            	update_post_meta($current_post_id, 'view', $temp_data_view);
	                            	update_post_meta($current_post_id, 'redirect_url', $temp_data_url);
	                                update_field('product_images', $temp_data_add_images, $temp_data['ID']);
	                                update_post_meta($temp_data['ID'], 'style_no', $temp_data_style_no);
	                        }
	                    	if ($post_type_name == 'global_event') {
	                            update_post_meta($temp_data['ID'], 'event_date', $temp_data_event_date);
	                            update_post_meta($temp_data['ID'], 'event_time', $temp_data_event_time);
	                    	}
                                
                                $total_update_count++;
                                
                            } else {
                                
                                $current_post_id = wp_insert_post( $temp_data, true );

                                if ($post_type_name == 'gallery') {
	                            add_post_meta($current_post_id, 'sizes', $temp_data_sizes);
	                            add_post_meta($current_post_id, 'colors', $temp_data_colors);
	                            add_post_meta($current_post_id, 'view', $temp_data_view);
	                            add_post_meta($current_post_id, 'redirect_url', $temp_data_url);
	                            update_field('product_images', $temp_data_add_images, $current_post_id);
                                add_post_meta($current_post_id, 'style_no', $temp_data_style_no);
	                    	}
	                    	if ($post_type_name == 'global_event') {
	                            add_post_meta($current_post_id, 'event_date', $temp_data_event_date);
	                            add_post_meta($current_post_id, 'event_time', $temp_data_event_time);
	                    	}
	                    	
                                $total_import_count++;
                            }
                            
                        } else if( $_POST['esb_cie_import_choice'] == 'ignore' ) {
                            
                            $current_post_id = esb_cie_check_post_by_slug( $current_post_data['post_name'], $post_type_name );
                            if( empty( $current_post_id ) ) {
                                
                                $current_post_id = wp_insert_post( $temp_data, true );

                                if ($post_type_name == 'gallery') {
	                            add_post_meta($current_post_id, 'sizes', $temp_data_sizes);
	                            add_post_meta($current_post_id, 'colors', $temp_data_colors);
	                            add_post_meta($current_post_id, 'view', $temp_data_view);
	                            add_post_meta($current_post_id, 'redirect_url', $temp_data_url);
                                update_field('product_images', $temp_data_add_images, $current_post_id);
                                add_post_meta($current_post_id, 'style_no', $temp_data_style_no);
	                    	}
	                    	if ($post_type_name == 'global_event') {
	                            add_post_meta($current_post_id, 'event_date', $temp_data_event_date);
	                            add_post_meta($current_post_id, 'event_time', $temp_data_event_time);
	                    	}
	                    	
                                $total_import_count++;
                                
                            } else {
                                
                                $total_ignore_count++;
                            }
                        }
                        
                        if( !empty( $current_post_id ) ) {
                            
                            //set post featured image
                            if( !empty( $current_post_data['post_image'] ) ) {
                                
                                set_post_thumbnail( $current_post_id, $current_post_data['post_image'] );
                            }
                            //set post terms
                            if( !empty( $post_taxonomies_data ) && is_array( $post_taxonomies_data ) ) {
                                
                                foreach ( $post_taxonomies_data as $post_taxonomy_key => $post_terms ) {
                                    
                                    if( !empty( $post_terms ) ) {
                                        
                                        $post_terms = trim( $post_terms );
                                        $post_terms = explode(',', $post_terms);
                                        
                                        //set terms
                                        wp_set_object_terms( $current_post_id, $post_terms, $post_taxonomy_key );
                                    }
                                }
                            }
                        }
                    }
                    /* Post Insert / Update / Ignore functionaly end */
                    
                } else {
                	
                    $num = count($data);
                    for ($c=0; $c < $num; $c++) {
                        $post_key_data[$c] = $data[$c];
                    }

                    
                }
                $row++;
            }
            fclose($handle);
        }
        $redirect_args = array(
                                    'page'         => 'esb-cie-import-export-page',
                                    'cie_type'     => 'post',
                                    'cie_import'   => $total_import_count,
                                    'cie_update'   => $total_update_count,
                                    'cie_ignore'   => $total_ignore_count
                                );
        $redirect_url = add_query_arg( $redirect_args, admin_url( 'admin.php' ) );
        wp_redirect( $redirect_url );
        exit;
    }
}
  
/**
 * Import csv data and insert terms
 */
function esb_cie_import_taxonomy_csv() {
    
    if( !empty( $_POST['esb_cie_import_term_csv'] ) && !empty( $_POST['esb_cie_csv_taxonomy_name'] )
        && !empty( $_FILES['esb_cie_import_file'] ) ) {
        
        $taxonomy_name = $_POST['esb_cie_csv_taxonomy_name'];
        
        $row = $total_import_count = $total_update_count = $total_ignore_count = 0;
        $term_key_data = array();
        if (($handle = fopen($_FILES['esb_cie_import_file']['tmp_name'], "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if( $row > 0 ) {
                    $num = count($data);
                    for ($c=0; $c < $num; $c++) {
                        
                        //String Conversation with UTF-8
                        $data[$c] = esb_cie_string_conversion( $data[$c] );
                        switch ( $term_key_data[$c] ) {

                            default:
                                    $temp_data[$term_key_data[$c]] = $data[$c];
                                    break;
                        }
                    }
                    
                    /* Post Insert / Update / Ignore functionaly start */
                    if( isset( $_POST['esb_cie_import_choice'] ) ) {

                        $parent_term_id = 0;
                        if( !empty( $temp_data['parent'] ) ) {
                            if( intval( $temp_data['parent'] ) ) {
                                $parent_term_id = $temp_data['parent'];
                            } else {
                                $parent_term = term_exists( $temp_data['parent'], $taxonomy_name ); // array is returned if taxonomy is given
                                $parent_term_id = isset( $parent_term['term_id'] ) ? $parent_term['term_id'] : 0; // get numeric term id
                            }
                        }
                        $term_slug          = isset( $temp_data['slug'] ) ? $temp_data['slug'] : '';
                        $term_title         = isset( $temp_data['name'] ) ? $temp_data['name'] : $term_slug;
                        $term_description   = isset( $temp_data['description'] ) ? $temp_data['description'] : '';
                            
                        $current_term_id = 0;
                        if( $_POST['esb_cie_import_choice'] == 'new' ) {
                            
                            //insert term
                            $current_term_id = esb_cie_insert_term( $taxonomy_name, $term_title, $term_slug, $term_description, $parent_term_id );
                            $total_import_count++;
                            
                        } else if( $_POST['esb_cie_import_choice'] == 'update' ) {
                            
                            $term = term_exists( $term_slug, $taxonomy_name );
                            if( $term !== 0 && $term !== null && !empty( $term['term_id'] ) ) {

                                $current_term_id = $term['term_id'];
                                wp_update_term( $current_term_id, $taxonomy_name, array(
                                    'name'       => $term_title,
                                    'slug'       => $term_slug,
                                    'description'=> $term_description,
                                    'parent'     => $parent_term_id
                                ));
                                $total_update_count++;
                                    
                            } else {

                                //insert term
                                $current_term_id = esb_cie_insert_term( $taxonomy_name, $term_title, $term_slug, $term_description, $parent_term_id );
                                $total_import_count++;
                            }
                            
                        } else if( $_POST['esb_cie_import_choice'] == 'ignore' ) {
                         
                            $term = term_exists( $term_slug, $taxonomy_name );
                            if( $term !== 0 && $term !== null ) {
                                $total_ignore_count++;
                            } else {
                                
                                //insert term
                                $current_term_id = esb_cie_insert_term( $taxonomy_name, $term_title, $term_slug, $term_description, $parent_term_id );
                                $total_import_count++;
                            }
                        }
                    }
                    /* Post Insert / Update / Ignore functionaly end */
                    
                } else {
                    $num = count($data);
                    for ($c=0; $c < $num; $c++) {
                        $term_key_data[$c] = $data[$c];
                    }
                }
                $row++;
            }
            fclose($handle);
        }
        $redirect_args = array(
                                    'page'         => 'esb-cie-import-export-page',
                                    'cie_type'     => 'term',
                                    'cie_import'   => $total_import_count,
                                    'cie_update'   => $total_update_count,
                                    'cie_ignore'   => $total_ignore_count
                                );
        $redirect_url = add_query_arg( $redirect_args, admin_url( 'admin.php' ) );
        wp_redirect( $redirect_url );
        exit;
    }
}

/**
 * Generate Posts CSV
 */
function esb_cie_generate_posts_csv() {
    
    if( !empty( $_POST['esb_cie_export_posts_csv'] ) && !empty( $_POST['esb_cie_column_name'] )
        && !empty( $_POST['esb_cie_export_post_type'] ) ) {
        
        $columns    = $_POST['esb_cie_column_name'];
        $file_name  = !empty( $_POST['esb_cie_csv_file_name'] ) ? $_POST['esb_cie_csv_file_name'] : 'export';
        $file_name  .= '-' . date( 'd-m-Y', time() );

        $posts_args = array(
                                    'post_type'     => $_POST['esb_cie_export_post_type'],
                                    'post_status'   => 'any',
                                    'posts_per_page'=> 1000
                                );
        $all_posts = get_posts( $posts_args );
        
        if( !empty( $all_posts ) ) { // check posts are exist

            $exports = '';

            foreach ( $columns as $column ) {

                $exports .= '"'.$column.'",';
            }
            $exports .="\n";

            foreach ( $all_posts as $post_data ) {

                $post_data = esb_cie_object_to_array( $post_data );

                foreach ( $columns as $column ) {

                    $column_data = isset( $post_data[$column] ) ? $post_data[$column] : '';
                    $pos = strpos( $column, 'tax-' );
                    if ($pos !== false) { // check taxonomy

                        $taxonomy = strstr( $column, '-' );
                        $taxonomy = trim( $taxonomy, '-' );

                        $terms = wp_get_post_terms( $post_data['ID'], $taxonomy, array( 'fields' => 'slugs' ) );
                        $column_data = !empty( $terms ) ? implode(', ', $terms) : '';

                    } else if( $column == 'post_author' ) {

                        $user           = get_user_by( 'id', $column_data );
                        $column_data    = isset( $user->user_login ) ? $user->user_login : '';

                    } else if( $column == 'post_parent' ) {

                        $parent_data    = !empty( $column_data ) ? get_post( $column_data ) : '';
                        $column_data    = isset( $parent_data->post_name ) ? $parent_data->post_name : '';

                    } else if( $column == 'post_image' ) {

                        $post_thumbnail_id  = get_post_thumbnail_id( $post_data['ID'] );
                        $attachment_post    = !empty( $post_thumbnail_id ) ? get_post( $post_thumbnail_id ) : '';
                        $column_data        = isset( $attachment_post->post_name ) ? $attachment_post->post_name : '';
                        
                    } else if( $column == 'sizes' ) {

                        $size = get_post_meta($post_data['ID'], 'sizes', true);
                        $column_data    = isset( $size ) ? $size : '';

                    } else if( $column == 'colors' ) {

                        $color = get_post_meta($post_data['ID'], 'colors', true);
                        $column_data    = isset( $color ) ? $color : '';

                    } else if( $column == 'view' ) {

                        $view = get_post_meta($post_data['ID'], 'view', true);
                        $column_data    = isset( $view ) ? $view : '';

                    } else if( $column == 'url' ) {

                        $url = get_post_meta($post_data['ID'], 'redirect_url', true);
                        $column_data    = isset( $url ) ? $url : '';

                    }  else if( $column == 'event_date' ) {

                        $event_date= get_post_meta($post_data['ID'], 'event_date', true);
                        if(!empty($event_date)) {
	                        $originalDate = $event_date;
                            $event_date = date("Y-m-d", strtotime($originalDate));
                        }
                        $column_data    = isset( $event_date) ? $event_date: '';

                    } else if( $column == 'event_time' ) {

                        $event_time= get_post_meta($post_data['ID'], 'event_time', true);
                        $column_data    = isset( $event_time) ? $event_time: '';

                    } else if( $column == 'style_no' ) {

	                    $style_no= get_post_meta($post_data['ID'], 'style_no', true);
	                    $column_data    = isset( $style_no) ? $style_no: '';

                    } else if ($column == 'additional-images') {
                    	$product_images = get_post_meta($post_data['ID'], 'product_images', true);
                    	$image_names = array();
                    	foreach ($product_images as $image_id) {
                    		array_push($image_names, get_the_title($image_id['ID']));
                    	}
                    	$column_data = isset($image_names) ? implode('|', $image_names) : '';
                    } else if ($column == 'item-size') {
                    	$item_size = get_post_meta($post_data['ID'], 'size', true);
                    	$column_data = isset( $item_size ) ? $item_size : '';
                    } else if ($column == 'item-color') {
                    	$item_color = get_post_meta($post_data['ID'], 'color', true);
                    	$column_data = isset( $item_color ) ? $item_color : '';
                    } else if ($column == 'store') {
                    	$item_store = get_post_meta($post_data['ID'], 'store', true);
                    	$column_data = isset( $item_store ) ? $item_store : '';
                    }
                    
                    $column_data = trim( $column_data );
                    $column_data = str_replace( "\n", "", $column_data );
                    $exports .= '"'.$column_data.'",';
                }
                $exports .="\n";
            }

            // Output to browser with appropriate mime type, you choose ;)
            header ('Content-type: text/x-csv; charset=UTF-8');
            header("Content-Disposition: attachment; filename=".$file_name.".csv");
            
            //String Conversation with UTF-8
            echo iconv("UTF-8", "ISO-8859-1//TRANSLIT", $exports);
            exit;
        }
    }
}

/**
 * Generate Terms CSV
 */
function esb_cie_generate_terms_csv() {
    
    if( !empty( $_POST['esb_cie_export_terms_csv'] ) && !empty( $_POST['esb_cie_column_name'] )
        && !empty( $_POST['esb_cie_export_taxonomy'] ) ) {
        
        $columns    = $_POST['esb_cie_column_name'];
        $file_name  = !empty( $_POST['esb_cie_csv_file_name'] ) ? $_POST['esb_cie_csv_file_name'] : 'export';
        $file_name  .= '-' . date( 'd-m-Y', time() );
        
        $all_terms = get_terms( $_POST['esb_cie_export_taxonomy'], array( 'hide_empty' => 0 ) );
        
        if( !empty( $all_terms ) ) { // check terms are exist

            $exports = '';

            foreach ( $columns as $column ) {

                $exports .= '"'.$column.'",';
            }
            $exports .="\n";

            foreach ( $all_terms as $term_data ) {

                $term_data = esb_cie_object_to_array( $term_data );

                foreach ( $columns as $column ) {

                    $column_data = isset( $term_data[$column] ) ? $term_data[$column] : '';
                    if( $column == 'parent' ) {

                        $parent_term    = !empty( $column_data ) ? get_term( $column_data, $_POST['esb_cie_export_taxonomy'] ) : '';
                        $column_data    = isset( $parent_term->slug ) ? $parent_term->slug : '';

                    }
                    $column_data = trim( $column_data );
                    $column_data = str_replace( "\n", "", $column_data );
                    $exports .= '"'.$column_data.'",';
                }
                $exports .="\n";
            }
            
            // Output to browser with appropriate mime type, you choose ;)
            header ('Content-type: text/x-csv; charset=UTF-8');
            header("Content-Disposition: attachment; filename=".$file_name.".csv");
            
            //String Conversation with UTF-8
            echo iconv("UTF-8", "ISO-8859-1", $exports);
            exit;
        }
    }
}

//add action to add admin menu page
add_action( 'admin_menu', 'esb_cie_admin_menu_pages' );

//add action to generate sample csv
add_action( 'admin_init', 'esb_cie_generate_sample_csv' );

//add action to import csv data and insert posts
add_action( 'admin_init', 'esb_cie_import_post_type_csv' );

//add action to import csv data and insert terms
add_action( 'admin_init', 'esb_cie_import_taxonomy_csv' );

//add action to generate posts csv
add_action( 'admin_init', 'esb_cie_generate_posts_csv' );

//add action to generate terms csv
add_action( 'admin_init', 'esb_cie_generate_terms_csv' );

?>