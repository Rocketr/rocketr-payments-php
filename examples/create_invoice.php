<?php
require_once realpath(__DIR__ . '/../') . '/init.php';

\RocketrPayments\RocketrPayments::setApiKey('APPLICATION_ID', 'APPLICATION_SECRET');

try {
	$i = new \RocketrPayments\Invoice();

	$i->addBillItem(9.99, 'Line Item 1', 10);
	$i->addBillItem(2.99, 'Line Item 2', 1);
	$i->addAcceptedPaymentMethod(\RocketrPayments\PaymentMethods::PaypalPayment);

	$i->setNotes('This is an test note');
	$i->setBuyerEmail('saad@rocketr.net');
	$i->addCustomField('Please enter your preferred color', '', false, false, 0);
	$i->addCustomField('internal_id', '2195342212', true, true, 0);
	$i->setBrowserRedirect('https://google.com');

	$result = $i->createInvoice();

	echo "Please visit click the following link to pay the invoice: " . $result['links']['invoice'] . "\n";

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