<?php /* Template Name: Add ViP Event */ ?>
<?php if(is_user_logged_in()) { ?>
<?php get_header(); ?>
<?php global $user_ID; ?>
<?php $user_info = get_userdata($user_ID); ?>
<div id="content">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12"> 
                <div class="container">
                    <?php while (have_posts()) : the_post(); ?>
                    <h1><span><?php the_title(); ?></span></h1>
                    <div><?php //the_content(); ?></div>
                    <?php endwhile; ?>  
                </div>                         
	        </div>
	    </div>
	</div>
</div>
<div id="my_acc">
	<div class="maincontent">
	    <div class="section group">
        <?php 
            if(isset($_POST['event_submit'])) {
                $i = 0;
                foreach($_POST['what'] as $what) {
                    if(!empty($what) && !empty($_POST['date'][$i]) && !empty($_POST['time'][$i]) && !empty($_POST['where'][$i])) {
                        $post = array(
                            'post_title' => $what,
                            'post_type' => 'event',
                            'post_status' => 'pending',                    
                            'post_content' => $_POST['des'],
                            'post_author' => $user_ID                                
                        ); 
                        $new_event = wp_insert_post( $post );
                        add_post_meta( $new_event, 'date', sanitize_text_field( $_POST['date'][$i] ) );
                        add_post_meta( $new_event, 'time', sanitize_text_field( $_POST['time'][$i] ) );
                        add_post_meta( $new_event, 'where', sanitize_text_field( $_POST['where'][$i] ) );
                        $i++;                        
                    }
                }                
                if($i != 0) {
                    echo "<div class='col span_12_of_12'><p class='successMsg'>Event Added...</p></div>";
                }
            }
        ?>
	        <div class="col span_12_of_12">
                <form id="joinus" style="max-width: none;" class="registration" action="" method="POST" enctype="multipart/form-data">
                    <div class="section group">
                        <div class="col span_4_of_12">
                            <div class="section group">
                                <div class="col span_12_of_12">
                                	<label><?php _e( 'What' ); ?></label>
                                	<input type="text" name="what[]" placeholder="" value="" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                	<label><?php _e( 'Date' ); ?></label>
                                	<input id="datepicker1" type="text" name="date[]" placeholder="" value="" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                	<label><?php _e( 'Time' ); ?></label>
                                	<input id="timepicker1" type="text" name="time[]" placeholder="" value="" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                	<label><?php _e( 'Where' ); ?></label>
                                    <textarea name="where[]"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col span_4_of_12">
                            <div class="section group">
                                <div class="col span_12_of_12">
                                	<label><?php _e( 'What' ); ?></label>
                                	<input type="text" name="what[]" placeholder="" value="" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                	<label><?php _e( 'Date' ); ?></label>
                                	<input id="datepicker2" type="text" name="date[]" placeholder="" value="" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                	<label><?php _e( 'Time' ); ?></label>
                                	<input id="timepicker2" type="text" name="time[]" placeholder="" value="" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                	<label><?php _e( 'Where' ); ?></label>
                                    <textarea name="where[]"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col span_4_of_12">
                            <div class="section group">
                                <div class="col span_12_of_12">
                                	<label><?php _e( 'What' ); ?></label>
                                	<input type="text" name="what[]" placeholder="" value="" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                	<label><?php _e( 'Date' ); ?></label>
                                	<input id="datepicker3" type="text" name="date[]" placeholder="" value="" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                	<label><?php _e( 'Time' ); ?></label>
                                	<input id="timepicker3" type="text" name="time[]" placeholder="" value="" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                	<label><?php _e( 'Where' ); ?></label>
                                    <textarea name="where[]"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <input type="submit" name="event_submit" value="Submit" class="submit-button" />
                        </div>
                    </div>
                </form>                         
	        </div>
	    </div>
	</div>
</div>
<script>
jQuery('#datepicker1').datetimepicker({
    timepicker:false,
    format:'m-d-Y'
});
jQuery('#timepicker1').datetimepicker({
    datepicker:false,
    format:'H:i'
});
jQuery('#datepicker2').datetimepicker({
    timepicker:false,
    format:'m-d-Y'
});
jQuery('#timepicker2').datetimepicker({
    datepicker:false,
    format:'H:i'
});
jQuery('#datepicker3').datetimepicker({
    timepicker:false,
    format:'m-d-Y'
});
jQuery('#timepicker3').datetimepicker({
    datepicker:false,
    format:'H:i'
});
</script>
<?php get_footer(); ?>
<?php } else {
   header('Location: '.get_bloginfo('home').'/sign-in'); 
} ?>