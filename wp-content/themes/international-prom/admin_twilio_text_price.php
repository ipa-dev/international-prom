<?php
    if ( $_POST['twilio_price_submit'] == 'Update' ) {
	    if(!empty($_POST['twilio_price'])){
	        update_option('twilio_price', $_POST['twilio_price']);
	    }
    }
    $twilio_price = get_option('twilio_price');
    if(empty($twilio_price)) {
	    $twilio_price = 0;
    }
?>
<div class="metabox-holder">
	<div class="meta-box-sortables ui-sortable">
		<div id="esb_cie_product_product_tag" class="postbox">

			<!-- settings box title -->
			<h3 class="hndle">
				<span style="vertical-align: top;">Twilio Text Price</span>
			</h3>

			<div class="inside acf-field">
                <form method="POST" action="">
                    <p>
                        $ <input type="text" name="twilio_price" value="<?php echo $twilio_price; ?>">
                        <input type="submit" name="twilio_price_submit" class="button button-primary button-large" value="Update">
                    </p>
                </form>
            </div><!-- .inside -->

		</div><!-- #settings -->
	</div><!-- .meta-box-sortables ui-sortable -->
</div>