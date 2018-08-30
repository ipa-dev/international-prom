<?php 
/* 
    Plugin Name: My Theme Options 
    Author: Jagannath    
*/
?>
<?php
add_action('admin_menu', 'themeoptions_admin_menu');
function themeoptions_admin_menu(){
    add_theme_page("Social Media", "Social Media", 'edit_themes', basename(__FILE__), 'themeoptions_page');
}
function themeoptions_page(){
    if ( $_POST['sub'] == 'Update Options' ) { themeoptions_update(); }
?>
<style>
    h4{
        margin-bottom: 0;
    }
    p{
        margin: 0 0 1em 0;
    }
</style>
<div class="wrap">
    <div id="icon-themes" class="icon32"><br /></div>
    <h2>Social Media</h2>

    <form method="POST" action="">
        
        <h4>Facebook</h4>
        <p><input type="text" name="facebook" value="<?php echo get_option('facebook'); ?>" /></p>

        <h4>Twitter</h4>
        <p><input type="text" name="twitter" value="<?php echo get_option('twitter'); ?>" /></p>

        <h4>Instagram</h4>
        <p><input type="text" name="instagram" value="<?php echo get_option('instagram'); ?>" /></p>

        <h4>Pinterest</h4>
        <p><input type="text" name="pinterest" value="<?php echo get_option('pinterest'); ?>" /></p>

        <h4>Linkedin</h4>
        <p><input type="text" name="linkedin" value="<?php echo get_option('linkedin'); ?>" /></p>

        <h4>RSS</h4>
        <p><input type="text" name="rss" value="<?php echo get_option('rss'); ?>" /></p>
        
        <p><input type="submit" name="sub" value="Update Options" class="button" /></p>
    </form>
</div>
<?php } ?>
<?php
function themeoptions_update(){
    if(!empty($_POST['twitter'])){update_option('twitter', $_POST['twitter']);}
    if(!empty($_POST['facebook'])){update_option('facebook', $_POST['facebook']);}
    if(!empty($_POST['instagram'])){update_option('instagram', $_POST['instagram']);}
    if(!empty($_POST['pinterest'])){update_option('pinterest', $_POST['pinterest']);}
    if(!empty($_POST['linkedin'])){update_option('linkedin', $_POST['linkedin']);}
    if(!empty($_POST['rss'])){update_option('rss', $_POST['rss']);}
}
?>