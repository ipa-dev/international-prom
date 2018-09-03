<div class="section group">
	<div class="col span_8_of_12">
		<label for="store_name"><?php _e( 'Text Limit' ); ?> <span class="required">*</span></label>
        <?php
        $text_limit = get_user_meta( $user_ID, 'text_limit', true );
        if(empty($text_limit)) {
            $text_limit = 0;
        }
        ?>
		<input type="number" min="0" name="text_limit" value="<?php echo $text_limit; ?>" required="required"/>
	</div>
    <div class="col span_4_of_12">
        <input style="margin-top: 15px; padding: 8px 16px;" type="submit" name="register" value="Update" class="submit-button" />
    </div>
</div>