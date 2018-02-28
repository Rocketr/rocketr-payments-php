<?php

namespace RocketrPayments;

class Currency {
	
	const USD = [
		'id' => 1,
		'long' => 'US Dollar',
		'short' => 'USD',
	];
		
	const EUR = [
		'id' => 2,      
		'long' => 'Euro',
		'short' => 'EUR',
	];
		
	const GBP = [
		'id' => 3,
		'long' => 'Pound',
		'short' => 'GBP',
	];
	
	const CAD = [
		'id' => 4,
		'long' => 'Canadian Dollar',
		'short' => 'CAD',
	];
	
	const AUD = [
		'id' => 5,
		'long' => 'Australian Dollar',
		'short' => 'AUD',
	];
	
	const JPY = [
		'id' => 6,
		'long' => 'Japanese Yen',
		'short' => 'JPY',
	];
	
	const CNY = [
		'id' => 7,
		'long' => 'Chinese Yuan',
		'short' => 'CNY',
	];
	
	const BTC = [
		'id' => 100,
		'long' => 'Bitcoin',
		'short' => 'BTC',
	];
	
	const BCC = [
		'id' => 101,
		'long' => 'Bitcoin Cash',
		'short' => 'BCH',
	];
	
	const ETH = [
		'id' => 102,
		'long' => 'Ethereum',
		'short' => 'ETH',
	];

	public static function getCurrencyFromId($id) {
		$id = intval($id);
		$refl = new ReflectionClass('Currency');
		foreach($refl->getConstants() as $const) {
			if($const['id'] == $id)
				return $const;
		}
		throw new RocketrPaymentsException('Currency Not Found ' . $id);
	}
	
	public static function getCurrencyFromShort($name) {
		$name = strtoupper($name);
		$refl = new ReflectionClass('Currency');
		foreach($refl->getConstants() as $const) {
			if($const['short'] == $name)
				return $const;
		}
		throw new RocketrPaymentsException('Currency Not Found ' . $name);
	}

	public static function isValidCurrency($currency) {
		return in_array($currency, Currency::getAllCurrencies());
	}
	
	public static function getAllCurrencies() {
		$refl = new ReflectionClass('Currency');
		return $refl->getConstants();
	}
}

?>