<?php
require_once('vendor/autoload.php');

$stripe = array(
    "secret_key"      => "sk_test_cmOSWkB6LfRQqP5wdO35ponC",
    "publishable_key" => "pk_test_56lPSQy6Xz1qEz7z3OzxYzoJ"
);
/*$stripe = array(
	"secret_key"      => "sk_live_8ML80gOt52EZFLFs5VVhmzHl",
	"publishable_key" => "pk_live_WwyD4xmmjkMu62xLANjGkqRy"
);*/

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>