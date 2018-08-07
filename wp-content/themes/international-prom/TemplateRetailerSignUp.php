<?php //session_start(); ?>
<?php /* Template Name: Retailer Sign Up Template */ ?>
<?php get_header(); ?>
<div id="content">
	<div class="maincontent">
	    <div class="section group">
	        <div class="col span_12_of_12"> 
                <div class="container">
                    <h1><span><?php the_title(); ?></span></h1> 
                </div>               
	        </div>
	    </div>
	</div>
</div>
<div id="content">
    <div class="maincontent noPadding">
        <div class="section group">
            <div class="col span_12_of_12">
            <?php
                    if(isset($_POST['register'])){
                        global $wpdb;
                        if(email_exists($_POST['email_id'])){
							echo '<div class="errorMsg">Email ID already present. Please use different Email ID.</div>';
						} else { 
                            $new_user_id = wp_insert_user(
                                array(
                                    'user_login'		=> $_POST['email_id'],
                                    'user_pass'			=> $_POST['pw1'],
                                    'user_email'		=> $_POST['email_id'],
                                    'first_name'		=> $_POST['fname'],
                                    'last_name'         => $_POST['lname'],
                                    'role'              => 'retailer',
                                    'user_nicename'     => $_POST['email_id'],
                                    'user_registered'	=> date('Y-m-d H:i:s')
                                )
                            );
                            add_user_meta( $new_user_id, 'address', sanitize_text_field( $_POST['address'] ) );
                            add_user_meta( $new_user_id, 'store_name', sanitize_text_field( $_POST['store_name'] ) );
                            add_user_meta( $new_user_id, 'website', sanitize_text_field( $_POST['website'] ) );
                            add_user_meta( $new_user_id, 'phone', sanitize_text_field( $_POST['phone'] ) );
                            add_user_meta( $new_user_id, 'country', sanitize_text_field( $_POST['country'] ) ); 
                            add_user_meta( $new_user_id, 'city', sanitize_text_field( $_POST['city'] ) );   
                            add_user_meta( $new_user_id, 'state', sanitize_text_field( $_POST['state'] ) );
                            add_user_meta( $new_user_id, 'postcode', sanitize_text_field( $_POST['zip'] ) );
                            add_user_meta( $new_user_id, 'store_des', sanitize_text_field( $_POST['store_des'] ) );
                            
                            if (isset($_POST['istilist_email']) && isset($_POST['istilist_password'])) {
                            	add_user_meta($new_user_id, 'istilist_email', $_POST['istilist_email']);
                            	add_user_meta($new_user_id, 'istilist_password', $_POST['istilist_password']);
                            }
                            
                            /*
                            $post_retailer = array(
                                'post_type' => 'retailer',
                                'post_status' => 'pending',
                                'post_author' => $new_user_id                                
                            ); 
                            $new_retailer = wp_insert_post( $post_retailer );
                            
                            add_user_meta( $new_user_id, 'store_id', $new_retailer );
                            */
                            
                            
                            $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $_POST['email_id']));
                            if(empty($key)) {
							    $key = wp_generate_password(20, false);
								$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $_POST['email_id']));
                            }
                            // Mail to admin
                                $admin_name = get_bloginfo('name');
                                $to = get_option('admin_email');
								$from = get_option('admin_email');
								$headers = 'From: '.$from . "\r\n";
                                $headers .= "MIME-Version: 1.0\n"; 
                                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
								$subject = "New Member registered: ".get_option('name'); 
                                $msg ='<strong>New Member registered</strong><br><br><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="15%"><strong>Name : </strong></td>
                                            <td>'.$_POST['fname'].' '.$_POST['lname'].'</td>
                                          </tr>
                                          <tr>
                                            <td><strong>Email : </strong></td>
                                            <td>'.$_POST['email_id'].'</td>
                                          </tr>
                                          <tr>
                                            <td><strong>City : </strong></td>
                                            <td>'.$_POST['city'].'</td>
                                          </tr>
                                          <tr>
                                            <td><strong>State : </strong></td>
                                            <td>'.$_POST['state'].'</td>
                                          </tr>
                                          <tr>
                                        </table><br><br>Regards,<br>'.$admin_name;
								wp_mail( $to, $subject, $msg, $headers );
                                // mail to user
                                $to1 = $_POST['email_id'];
								$from1 = get_option('admin_email');
								$headers1 = 'From: '.$from1. "\r\n";
                                $headers1 .= "MIME-Version: 1.0\n"; 
                                $headers1 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
								$subject1 = "Activate your account"; 
                                $msg1 = 'Activation Link :<a href="'.get_site_url().'/activation?key='.$key.'" target="_blank">'.get_site_url().'/activation?key='.$key.'</a>'; 
								wp_mail( $to1, $subject1, $msg1, $headers1 );
                                header("Location: ".get_bloginfo('home')."/thank-you/?action=".encripted('registration'));
                            }
                        }
                    ?>
                    <form id="joinus" class="registration" action="" method="post" enctype="multipart/form-data">
                        <div class="section group">
                            <div class="col span_12_of_12">
                    	       <label for="store_name"><?php _e( 'Store name' ); ?> <span class="required">*</span></label>
                    	       <input type="text" name="store_name" placeholder="" required="required" />
                            </div>
                        </div>
                        <div class="section group">
                            <div class="col span_6_of_12">
                            	<label for="reg_billing_first_name"><?php _e( 'First name' ); ?> <span class="required">*</span></label>
                            	<input type="text" name="fname" placeholder="" required="required" />
                            </div>
                            <div class="col span_6_of_12">
                    	       <label for="reg_billing_last_name"><?php _e( 'Last name' ); ?> <span class="required">*</span></label>
                    	       <input type="text" name="lname" placeholder="" required="required" />
                            </div>
                        </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                				<label for=""><?php _e( 'Address (Select Location)' ); ?> <span class="required">*</span></label>
                				<div class="form-group">
                                    <input type="text" required="required" name="address" value="" class="placepicker form-control" data-map-container-id="collapseOne"/>
                                </div>
                                <div id="collapseOne" class="collapse">
                                    <div class="placepicker-map thumbnail"></div>
                                </div>
                            </div>
                        </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                        		<label for="city"><?php _e( 'City' ); ?> <span class="required">*</span></label>
                                <input type="text" name="city" />
                            </div>
                        </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                                <label for="address"><?php _e( 'State' ); ?> <span class="required">*</span></label>
                                <input type="text" name="state" />
                            </div>
                        </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                                <label for="zip"><?php _e( 'Zip Code' ); ?> <span class="required">*</span></label>
                                <input type="text" name="zip" />
                            </div>
                        </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                                <label for="address"><?php _e( 'Country' ); ?> <span class="required">*</span></label>
                                <select class="country" required="required" name="country" data-constraints="@Required @Country">
                                    <option>Select Country</option>
                                    <?php 
                                        $url = TEMPLATEPATH.'/countries.xml';
                                        $xml = simplexml_load_file($url);
                                        foreach($xml->country as $country) {
                                    ?>
                                    <option value="<?php echo $country['code']; ?>"><?php echo $country; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                				<label for="reg_email"><?php _e( 'Phone Number' ); ?> <span class="required">*</span></label>
                				<input type="text" name="phone" placeholder="" required="required" />
                            </div>
                        </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                				<label for="website"><?php _e( 'Website' ); ?> <span class="required">*</span></label>
                				<input type="text" name="website" placeholder="" required="required" />
                            </div>
                        </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                				<label for="reg_email"><?php _e( 'Email address' ); ?> <span class="required">*</span></label>
                				<input type="text" name="email_id" id="e1" placeholder="To enter multiple email use comma separated values" title="To enter multiple email use comma separated values" required="required" />
                            </div>
                        </div>
                        <!--<div class="section group">
                            <div class="col span_12_of_12">
                				<label for="reg_email"><?php //_e( 'Verify Email address' ); ?> <span class="required">*</span></label>
                				<input type="text" name="email_id1" id="e2" placeholder="" required="required" oninput="validateEmail(document.getElementById('e1'), this);" onfocus="validateEmail(document.getElementById('e1'), this);" />
                            </div>
                        </div>-->
                        <div class="section group">
                            <div class="col span_12_of_12">
            					<label for="reg_password"><?php _e( 'Password' ); ?> <span class="required">*</span></label>
            					<input type="password" name="pw1" id="pw1" placeholder="" required="required" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" />
                            </div>
                        </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                        		<label for="reg_password"><?php _e( 'Verify Password' ); ?> <span class="required">*</span></label>
                        		<input type="password" id="pw2" name="pw2" class="input-text" oninput="validatePass(document.getElementById('pw1'), this);" onfocus="validatePass(document.getElementById('pw1'), this);" required="required" />
                            </div>
                        </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                				<label for="store_des"><?php _e( 'Store Description ' ); ?></label>
                				<textarea name="store_des"></textarea>
                            </div>
                        </div>
                        <div class="section group">
                        	<div class="col span_12_of_12">
                        		<label for="istilist_email">iSTiLiST E-mail</label>
                        		<input type="text" name="istilist_email" id="istilist_email"/>
                        	</div>
                        </div>
                        <div class="section group">
                        	<div class="col span_12_of_12">
                        		<label for="istilist_password">iSTiLiST Password</label>
                        		<input type="password" name="istilist_password" id="istilist_password"/>
                        	</div>
                        </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                                <span><input id="terms" type="checkbox" name="terms" value="" required="required" /></span>
                                <span id="termtext">Accept <a href="<?php bloginfo('home'); ?>/terms-and-conditions/" target="_blank" style="color: #EA5736; text-decoration: none;">terms and conditions</a></span> <span class="required">*</span>
                                <input type="submit" name="register" value="Join Us" class="submit-button" />
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>