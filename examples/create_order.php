<?php
require_once realpath(__DIR__ . '/../') . '/init.php';

\RocketrPayments\RocketrPayments::setApiKey('APPLICATION_ID', 'APPLICATION_SECRET');

try {
	$o = new \RocketrPayments\Order();

	$o->setPaymentMethod(\RocketrPayments\PaymentMethods::BitcoinPayment);
	$o->setAmount(12.31);
	$o->setNotes('This is an test note');
	$o->setBuyerEmail('saad@rocketr.net');
	$o->addCustomField('internal_id', '2195342212');
	$o->setIpnUrl('https://rocketr.net/webhook.php');

	$result = $o->createOrder();

	echo 'Please send ' . $result['paymentInstructions']['amount']  . $result['paymentInstructions']['currencyText'] . ' to ' . $result['paymentInstructions']['address'];

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