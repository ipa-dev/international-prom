<script src="https://checkout.stripe.com/checkout.js"></script>
<?php
$twilio_price = get_option('twilio_price');
global $user_ID;
$user_info = get_userdata( $user_ID );       
?>
<div class="section group">
	<div class="col span_8_of_12">
		<label for="store_name"><?php _e( 'Text Credit' ); ?> </label>
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
        <a id="stripeButton" style="margin-top: 15px; padding: 8px 16px; float: right;" class="submit-button" href="javascript:void(0);
">Buy</a>
    </div>
</div>
<script>
var handler = StripeCheckout.configure({
    key: 'pk_test_zjHNQ9QpY7bmODEgoDdfj6Xn',
    image: 'https://internationalprom.com/wp-content/uploads/2015/11/logo.png',
    locale: 'auto',
    token: function(token) {
        var text_limit = jQuery('input[name="text_limit"]').val();
        var text_limit_hidden = jQuery('input[name="text_limit_hidden"]').val();
        var buy_text_credit = text_limit - text_limit_hidden;
        var amount = <?php echo $twilio_price ?>*buy_text_credit*100;
        jQuery.ajax({
            type: "POST",
            url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
            action: 'stripe_payment',
            stripeToken: token.id,
            stripeEmail: token.email,
            amount: amount,
            text_limit: text_limit
        }
    }
});

document.getElementById('stripeButton').addEventListener('click', function(e) {
    var text_limit = jQuery('input[name="text_limit"]').val();
    var text_limit_hidden = jQuery('input[name="text_limit_hidden"]').val();
    var buy_text_credit = text_limit - text_limit_hidden;
    var amount = <?php echo $twilio_price ?>*buy_text_credit*100;
    if(text_limit != '' || text_limit != 0) {
        // Open Checkout with further options:
        handler.open({
            name: '<?php bloginfo( 'name' ); ?>',
            description: 'Buy ' + buy_text_credit + ' texts',
            amount: amount,
            currency: 'usd',
            email: '<?php echo $user_info->user_email; ?>',
            allowRememberMe: false
        });
    }
  e.preventDefault();
});

// Close Checkout on page navigation:
window.addEventListener('popstate', function() {
  handler.close();
});
</script>