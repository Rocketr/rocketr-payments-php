<?php
require_once realpath(__DIR__ . '/../') . '/init.php';

\RocketrPayments\RocketrPayments::setApiKey('APPLICATION_ID', 'APPLICATION_SECRET');

try {
	
	print_r(\RocketrPayments\Order::getOrders('includeTimedOut=false&perPage=10'));

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