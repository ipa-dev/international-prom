<div class="section group">
	<div class="col span_8_of_12">
		<label for="store_name"><?php _e( 'Text Limit' ); ?> <span class="required">*</span></label>
		<input type="text" name="text_limit" placeholder="" value="<?php echo get_user_meta( $user_ID, 'text_limit', true ); ?>" required="required"/>
	</div>
    <div class="col span_4_of_12">
    </div>
</div>