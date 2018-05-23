<?php /* Template Name: Favorite add */ ?>
<?php if(is_user_logged_in()) { 
    global $user_ID;
    $post_id = $_GET['id'];
    $fav = get_user_meta($user_ID, 'fav', true);
    $fav_arr = array();
    if(empty($fav)) {
        $fav_arr[0] = $post_id;
    }
    else {
        $fav_arr = json_decode($fav); 
        array_push($fav_arr, $post_id);
    }
    update_user_meta($user_ID, 'fav', json_encode($fav_arr));
    echo 'addSuccess';
    //echo json_encode($fav_arr);
} else {
   header('Location: '.get_bloginfo('home').'/sign-in'); 
}
?>