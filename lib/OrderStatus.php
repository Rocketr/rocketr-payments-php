<?php

namespace RocketrPayments;

abstract class OrderStatus {
	
	const UNPAID = -1;
	const NEW_ORDER = 0;
	const WAITING_FOR_PAYMENT = 1;
	const ERROR_PARTIAL_PAYMENT_RECEIVED = 2;
	const FULL_PAYMENT_RECEIVED = 3;
	const PRODUCT_DELIVERED = 4; //AKA SUCCESS
	const REFUNDED = 5;
		
	const UNKNOWN_ERROR = 6;
	
	const PAYPAL_PENDING = 8;
	const PAYPAL_OTHER = 9;
	const PAYPAL_REVERSED = 10;
	const PAYPAL_VOIDED = 11;
	
	const STRIPE_AUTO_REFUND = 20;
	const STRIPE_DECLINED = 21;
	const STRIPE_DISPUTED = 22;
	

	public static function getStatusName($value) {
		$class = new \ReflectionClass(__CLASS__);
		$constants = array_flip($class->getConstants());

		return $constants[$value];
	}

	public static function isValidStatus($value) {
		$class = new \ReflectionClass(__CLASS__);
		$constants = array_flip($class->getConstants());

		return array_key_exists($value, $constants);
	}
}


?>