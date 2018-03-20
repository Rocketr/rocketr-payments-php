<?php

namespace RocketrPayments;

use RocketrPayments\RocketrWebhookException;

class Webhook {

	public static function constructWebhook($postVariables, $headerSignature, $ipnSecret) {

		if(!isset($postVariables) || sizeof($postVariables) === 0 || !isset($headerSignature)) {
			throw new RocketrPaymentsException('Received invalid webhook');
		}

		if(isset($postVariables['custom_fields']))
			$postVariables['custom_fields'] = html_entity_decode($postVariables['custom_fields']);

		$hmac = hash_hmac("sha512", json_encode($postVariables), trim($ipnSecret));
		if ($hmac !== $headerSignature) {
			throw new RocketrPaymentsException('IPN Hash does not match'); 
		}
		
		return new Order($postVariables);
	}

}

?>