<?php

namespace RocketrPayments;

use RocketrPayments\InvoiceStatus;
use RocketrPayments\PaymentMethods;
use RocketrPayments\ShippingAddress;
use RocketrPayments\RocketrPaymentsException;


class Invoice {
	//@var String | This is an identifier for the invoice
	private $invoiceIdentifier;
	
	//@var String | This is the email of the buyer. If the send_buyer_emails flag is set true on order creation, the buyer will receive an email about the order to this email address
	private $buyerEmail;
	
	//@var InvoiceStatus | The status of the invoice. Please see the Invoice Status specifications for details.
	private $status;
	
	//@var decimal | The amount of the invoice. The amount is in the currency of the invoice.
	private $amount;
	
	//@var Currency | The currency of the invoice. Please see the Currency specifications for details.
	private $currency;
	
	//@var JSON Array |	An array list of accepted payment methods. Please see the Payment Method specifications for details.
	private $acceptedPaymentMethods;
	
	//@var JSON Array |	Please see the BillItems specifications for details.
	private $billItems;
	
	//@var String | Whether or not an address should be asked when the buyer pays
	private $shouldCollectAddress;
	
	//@var JSON Array |	An array of Custom Fields that the buyer needs to fill out when completing the order. Please note that you can pass through your own custom fields by setting the passthrough fields to true and providing a default value for a custom field object.
	private $customFields;
	
	//@var String | Any notes about the invoice.
	private $notes;
	
	//@var String | The URL where webhooks will be sent for this invoice (e.g. when an order is created etc)
	private $ipnUrl;
	
	//@var String | The URL to redirect the buyer to after the invoice is paid
	private $browserRedirect;

	//@var timestamp | The timestamp when the invoice was created.
	private $createdAt;

	private $invoiceLink;

	private $apiHandler;

	public function __construct() {
		$this->apiHandler = RocketrPayments::getApiHandler();
		$this->invoiceIdentifier = '';
		$this->buyerEmail = '';
		$this->status = InvoiceStatus::DRAFT;
		$this->amount = 0;
		$this->currency = Currency::USD;
		$this->acceptedPaymentMethods = [];
		$this->billItems = [];
		$this->shouldCollectAddress = false;
		$this->customFields = [];
		$this->notes = '';
		$this->ipnUrl = '';
		$this->browserRedirect = '';
		$this->createdAt = -1;
		$this->invoiceLink = '';
	}

	/**
	 * Creates an invoice
	 *
	 * @return associative array, response 
	 */
	public function createInvoice() {

		if(strlen($this->invoiceIdentifier) > 0)
			throw new RocketrPaymentsException('Error creating an invoice, this invoice object has already been created');

		$response = $this->apiHandler->performPostRequest('/invoices/create', $this->serializeJSON());

		if(isset($response['success']) && $response['success'] == true) {
			$this->invoiceIdentifier = $response['invoice']['identifier'];
			$this->invoiceLink = $response['links']['invoice'];
		}

		return $response[1];
	}

	/**
	 * Fn responsible for convering the current class to a JSON encoded string. Used to make the POST data when an invoice is created 
	 * 
	 * @return JSON encoded string of the current class
	 */
	private function serializeJSON() {
		$toEncode = [
			'buyerEmail' => $this->buyerEmail,
			'currency' => $this->currency['short'],
			'acceptedPaymentMethods' => $this->acceptedPaymentMethods,
			'shouldCollectAddress' => $this->shouldCollectAddress,
			'customFields' => $this->customFields,
			'notes' => $this->notes,
			'ipnUrl' => $this->ipnUrl,
			'browserRedirect' => $this->browserRedirect
		];

		if($this->amount > 0)
			$toEncode['amount'] = $this->amount;
		if(sizeof($this->billItems) > 0)
			$toEncode['billItems'] = $this->billItems;
		
		return json_encode($toEncode);
	}

	public function getInvoiceLink() {
		return $this->invoiceLink;
	}

	/**
	 * Adds a bill item
	 * 
	 * @return true
	 */
	public function addBillItem($price, $description = '', $quantity = 1) {
		$this->billItems[] = [
			'price' => $price,
			'description' => $description,
			'quantity' => $quantity
		];
		return true;
	}


	public function setStatus($status) {
		if(!InvoiceStatus::isValidStatus($status))
			throw new RocketrPaymentsException('Attempting to set the status to an unknown value');
		$this->status = $status;
	}

	
	public function setCurrency($currency){
		if(!Currency::isValidCurrency($currency))
			throw new RocketrPaymentsException('Attempting to set the currency to an unknown value');
		$this->currency = $currency;
	}


	public function addAcceptedPaymentMethod($acceptedPaymentMethod){

		if(is_array($acceptedPaymentMethod)) {
			$this->acceptedPaymentMethods[] = $acceptedPaymentMethod['name'];
			return true;
		}

		try {
			if(is_int($acceptedPaymentMethod))
				$this->acceptedPaymentMethods[] = PaymentMethods::getConstFromId($acceptedPaymentMethod)['name'];
			elseif(is_string($acceptedPaymentMethod))
				$this->acceptedPaymentMethods[] = PaymentMethods::getConstFromName($acceptedPaymentMethod)['name'];
			else
				return false;

			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * Add a custom field to the invoice. Custom Fields need to be filled out by the buyer when completing the invoice. Please note that you can pass through your own custom fields by setting the passthrough fields to true and providing a default value for a custom field object.	
	 *
	 * @param text | String | The text to display to the buyer
	 * @param default | String | The default value of the field 
	 * @param passthrough | bool | If this is true, the buyer will not be shown the custom field and the 'default' value will be used
	 * @param required | bool | Whether or not this is required to be filled before purchase
	 * @param type | [0, 1, 2] | The type of fashion to ask the buyer in (0 = textfield, 1 = textarea, 2 = checkbox
	 *
	 */
	public function addCustomField($text, $default = '', $passthrough = false, $required = false, $type = 0) {
		$this->customFields[] = [
			'name' => $text,
			'default' => $default,
			'passthrough' => $passthrough,
			'required' => $required,
			'type' => $type
		];
	}
	

	public function getInvoiceIdentifier(){
		return $this->invoiceIdentifier;
	}

	public function getBuyerEmail(){
		return $this->buyerEmail;
	}

	public function setBuyerEmail($buyerEmail){
		$this->buyerEmail = $buyerEmail;
	}

	public function getStatus(){
		return $this->status;
	}

	public function getAmount(){
		return $this->amount;
	}

	public function setAmount($amount){
		$this->amount = $amount;
	}

	public function getCurrency(){
		return $this->currency;
	}

	public function getAcceptedPaymentMethods(){
		return $this->acceptedPaymentMethods;
	}

	public function getBillItems(){
		return $this->billItems;
	}

	public function setBillItems($billItems){
		$this->billItems = $billItems;
	}

	public function getShouldCollectAddress(){
		return $this->shouldCollectAddress;
	}

	public function setShouldCollectAddress($shouldCollectAddress){
		$this->shouldCollectAddress = $shouldCollectAddress;
	}

	public function getCustomFields(){
		return $this->customFields;
	}


	public function getNotes(){
		return $this->notes;
	}

	public function setNotes($notes){
		$this->notes = $notes;
	}

	public function getIpnUrl(){
		return $this->ipnUrl;
	}

	public function setIpnUrl($ipnUrl){
		$this->ipnUrl = $ipnUrl;
	}

	public function getBrowserRedirect(){
		return $this->browserRedirect;
	}

	public function setBrowserRedirect($browserRedirect){
		$this->browserRedirect = $browserRedirect;
	}

	public function getCreatedAt(){
		return $this->createdAt;
	}
}
?>