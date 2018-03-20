<?php
require_once realpath(__DIR__ . '/../') . '/init.php';

\RocketrPayments\RocketrPayments::setApiKey('APPLICATION_ID', 'APPLICATION_SECRET');

try {
	
	$postFields = $_POST;

	$serverHash = $_SERVER['HTTP_IPN_HASH'];
	$ipnSecret = 'myun1qu3s3cr3t';


	var_dump(RocketrPayments\Webhook::constructWebhook($postFields, $serverHash, $ipnSecret));

}  catch (\RocketrPayments\RocketrPaymentsApiException $e) {
	echo "API Exception\n";
	echo $e->getMessage() . "\n";
} catch (\RocketrPayments\RocketrPaymentsException $e) {
	echo "RocketrPayments Exception\n";
	echo $e->getMessage() . "\n";
} catch (Exception $e) {
	echo $e->getMessage() . "\n";
}


?>