<div class="section group">
  <div class="col span_12_of_12">
     <label for="store_name"><?php _e( 'Store name' ); ?> <span class="required">*</span></label>
     <input type="text" name="store_name" placeholder="" value="<?php echo get_user_meta( $user_ID, 'store_name', true ); ?>" required="required"/>
  </div>
</div>
<div class="section group">
  <div class="col span_12_of_12">
     <label for="store_address"><?php _e( 'Store Address' ); ?><span class="required">*</span></label>
     <input type="text" name="address" placeholder="" value="<?php echo get_user_meta( $user_ID, 'store_address', true ); ?>" required="required"/>
  </div>
</div>
<div class="section group">
  <div class="col span_12_of_12">
   <label for="store_city"><?php _e( 'Store City' ); ?> <span class="required">*</span></label>
   <input type="text" name="store_city" placeholder="" value="<?php echo get_user_meta( $user_ID, 'store_city', true ); ?>" required="required"/>
  </div>
</div>
<div class="section group">
  <div class="col span_12_of_12">
     <label for="store_state"><?php _e( 'Store State' ); ?> <span class="required">*</span></label>
     <input type="text" name="store_state" placeholder="" value="<?php echo get_user_meta( $user_ID, 'store_state', true ); ?>" required="required"/>
  </div>
</div>
<div class="section group">
  <div class="col span_12_of_12">
     <label for="store_zip"><?php _e( 'Store Zip' ); ?> <span class="required">*</span></label>
     <input type="text" name="store_zip" placeholder="" value="<?php echo get_user_meta( $user_ID, 'store_zip', true ); ?>" required="required"/>
  </div>
</div>
<div class="section group">
  <div class="col span_12_of_12">
    <label for="store_des"><?php _e( 'Store Description ' ); ?></label>
    <textarea name="store_des"><?php echo get_user_meta( $user_ID, 'store_des', true ); ?></textarea>
  </div>
</div>
