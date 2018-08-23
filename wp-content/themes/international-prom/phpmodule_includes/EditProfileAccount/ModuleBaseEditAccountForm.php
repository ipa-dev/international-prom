<div class="section group">
  <div class="col span_6_of_12">
    <label for="reg_billing_first_name"><?php _e( 'First name' ); ?> <span class="required">*</span></label>
    <input type="text" name="fname" placeholder="" value="<?php echo $user_info->first_name; ?>" required="required" />
  </div>
  <div class="col span_6_of_12">
     <label for="reg_billing_last_name"><?php _e( 'Last name' ); ?> <span class="required">*</span></label>
     <input type="text" name="lname" placeholder="" value="<?php echo $user_info->last_name; ?>" required="required" />
  </div>
</div>
<div class="section group">
  <div class="col span_12_of_12">
    <label for="reg_email"><?php _e( 'Email address' ); ?> <span class="required">*</span></label>
    <input type="text" name="email_id" id="e1" placeholder="" required="required" value="<?php echo $user_info->user_email; ?>" />
  </div>
</div>
<div class="section group">
  <div class="col span_12_of_12">
    <label for="reg_email"><?php //_e( 'Varify Email address' ); ?> <span class="required">*</span></label>
    <input type="text" name="email_id1" id="e2" placeholder="" required="required" value="<?php echo $user_info->user_email; ?>" oninput="validateEmail(document.getElementById('e1'), this);" onfocus="validateEmail(document.getElementById('e1'), this);" />
  </div>
</div>
<div class="section group">
  <div class="col span_12_of_12">
    <label for="reg_password"><?php _e( 'Password' ); ?></label>
    <input type="password" name="pw1" id="pw1" placeholder="" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" />
  </div>
</div>
<div class="section group">
  <div class="col span_12_of_12">
    <label for="reg_password"><?php _e( 'Confirm Password' ); ?></label>
    <input type="password" id="pw2" name="pw2" class="input-text" oninput="validatePass(document.getElementById('pw1'), this);" onfocus="validatePass(document.getElementById('pw1'), this);" />
  </div>
</div>
