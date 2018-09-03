<script src="https://checkout.stripe.com/checkout.js"></script>
<div class="section group">
	<div class="col span_8_of_12">
		<label for="store_name"><?php _e( 'Text Limit' ); ?> <span class="required">*</span></label>
        <?php
        $text_limit = get_user_meta( $user_ID, 'text_limit', true );
        if(empty($text_limit)) {
            $text_limit = 0;
        }
        ?>
        <input type="hidden" name="text_limit_hidden" value="<?php echo $text_limit; ?>" required="required"/>
		<input type="number" min="0" name="text_limit" value="<?php echo $text_limit; ?>" required="required"/>
	</div>
    <div class="col span_4_of_12">
        <a style="margin-top: 15px; padding: 8px 16px; float: right;" class="submit-button" href="javascript:void(0);">Buy</a>
    </div>
</div>
<script>
var handler = StripeCheckout.configure({
  key: 'pk_test_zjHNQ9QpY7bmODEgoDdfj6Xn',
  image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
  locale: 'auto',
  token: function(token) {
    // You can access the token ID with `token.id`.
    // Get the token ID to your server-side code for use.
  }
});

document.getElementById('customButton').addEventListener('click', function(e) {
  // Open Checkout with further options:
  handler.open({
    name: 'Demo Site',
    description: '2 widgets',
    amount: 2000
  });
  e.preventDefault();
});

// Close Checkout on page navigation:
window.addEventListener('popstate', function() {
  handler.close();
});
</script>