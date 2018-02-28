<?php

namespace RocketrPayments;

class ShippingAddress {
	
	//@var String | Name of recipient
	private $addressName;
	
	//@var String | Address Line 1
	private $addressLine1;
	
	//@var String | City
	private $addressCity;
	
	//@var String | State/Province
	private $addressState;
	
	//@var String | Zip/Postal Code
	private $addressZip;
	
	//@var String | Country
	private $addressCountry;
	
	//@var String | Address Line 2
	private $addressLine2;

	public function __construct($address_name = '', $address_line1 = '', $address_line2 = '', $address_city = '', $address_state = '', $address_zip = '', $address_country = '') {
		$this->addressName = $address_name;
		$this->addressLine1 = $address_line1;
		$this->addressCity = $address_city;
		$this->addressState = $address_state;
		$this->addressZip = $address_zip;
		$this->addressCountry = $address_country;
		$this->addressLine2 = $address_line2;
	}

	/**
	 * Casting to array using (array) does not work as it includes the namespace
	 */
	public function asArray() {
		return [
			'addressName' => $this->addressName,
			'addressLine1' => $this->addressLine1,
			'addressCity' => $this->addressCity,
			'addressState' => $this->addressState,
			'addressZip' => $this->addressZip,
			'addressCountry' => $this->addressCountry,
			'addressLine2' => $this->addressLine2,
		];
	}

	public function serializeJSON() {
		return json_encode($this->asArray());
	}

	public function getAddressName(){
		return $this->addressName;
	}

	public function setAddressName($addressName){
		$this->addressName = $addressName;
	}

	public function getAddressLine1(){
		return $this->addressLine1;
	}

	public function setAddressLine1($addressLine1){
		$this->addressLine1 = $addressLine1;
	}

	public function getAddressCity(){
		return $this->addressCity;
	}

	public function setAddressCity($addressCity){
		$this->addressCity = $addressCity;
	}

	public function getAddressState(){
		return $this->addressState;
	}

	public function setAddressState($addressState){
		$this->addressState = $addressState;
	}

	public function getAddressZip(){
		return $this->addressZip;
	}

	public function setAddressZip($addressZip){
		$this->addressZip = $addressZip;
	}

	public function getAddressCountry(){
		return $this->addressCountry;
	}

	public function setAddressCountry($addressCountry){
		$this->addressCountry = $addressCountry;
	}

	public function getAddressLine2(){
		return $this->addressLine2;
	}

	public function setAddressLine2($addressLine2){
		$this->addressLine2 = $addressLine2;
	}
}

?>