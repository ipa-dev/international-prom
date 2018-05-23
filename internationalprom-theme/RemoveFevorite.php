<?php /* Template Name: Favorite remove */ ?>
<?php if(is_user_logged_in()) { 
    global $user_ID;
    $post_id = $_GET['id'];
    $fav = get_user_meta($user_ID, 'fav', true);
    $fav_arr = array();
    $fav_arr_new = array();
    if(!empty($fav)) {    
        $fav_arr = json_decode($fav);
        //print_r($fav_arr);    
        //$pos = array_search($post_id, $fav_arr);
        //unset($fav_arr[$pos]);
        foreach($fav_arr as $fav_ar){
            if($fav_ar != $post_id){
                array_push($fav_arr_new, $fav_ar);
            }
        }
        update_user_meta($user_ID, 'fav', json_encode($fav_arr_new));
    }
    echo 'removeSuccess';
} else {
   header('Location: '.get_bloginfo('home').'/login'); 
}
?>