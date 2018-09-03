<?php
require_once('vendor/autoload.php');

$stripe = array(
    "secret_key"      => "sk_test_ncvqc6VunhBf59GyfpN9A2i7",
    "publishable_key" => "pk_test_zjHNQ9QpY7bmODEgoDdfj6Xn"
);
/*$stripe = array(
	"secret_key"      => "sk_live_8ML80gOt52EZFLFs5VVhmzHl",
	"publishable_key" => "pk_live_WwyD4xmmjkMu62xLANjGkqRy"
);*/

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>