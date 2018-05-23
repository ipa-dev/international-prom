<div id="footer">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_2_of_12"> 
                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-logo') ) : ?> <?php endif; ?>
	        </div>
	        <div class="col span_3_of_12">
                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-about') ) : ?> <?php endif; ?>
            </div>
            <div class="col span_2_of_12">
                <div class="footer_nav"><?php wp_nav_menu(array('theme_location' => 'footermenu')); ?></div>
             </div>
            <div class="col span_5_of_12">
                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('newsletter') ) : ?> <?php endif; ?>
                <div class="footer_social_nav">
                    <ul>
                        <li><a href="<?php echo get_option('facebook'); ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="<?php echo get_option('instagram'); ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="<?php echo get_option('pinterest'); ?>" target="_blank"><i class="fa fa-pinterest"></i></a></li>
                        <li><a href="<?php echo get_option('twitter'); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="<?php echo get_option('linkedin'); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div>
            </div>
	    </div>
	</div>
</div>
<div id="copyright">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12"> 
                <p>© Copyright <?php echo date('Y'); ?> <a href="#">International Prom Association</a>. All rights reserved.</p>                       
	        </div>
	    </div>
	</div>
</div>
<!-- Multiple Image Upload -->
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/multiupload.js"></script>
<script type="text/javascript">
var config = {
	support : "image/jpg,image/png,image/bmp,image/jpeg,image/gif",		// Valid file formats
	form: "demoFiler",					// Form ID
	dragArea: "dragAndDropFiles",		// Upload Area ID
	uploadUrl: "<?php bloginfo('url'); ?>/gallery-image-upload-ajax",				// Server side upload url
    refreshUrl: "<?php bloginfo('url'); ?>/gallery-content-refresh-ajax",
    Url: "<?php bloginfo('template_directory'); ?>"
}
jQuery(document).ready(function(){
	initMultiUploader(config);
});
</script>


<?php wp_footer(); ?>
</body>
</html>