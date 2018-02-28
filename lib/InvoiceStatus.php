<?php

namespace RocketrPayments;

abstract class InvoiceStatus {
    
	const DRAFT = 0;
	const SENT = 1;
	const PENDING = 2;
	const PARTIAL_PAID = 3;
	const PAID = 100;
    
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