<?php

namespace RocketrPayments;

class RocketrPaymentsApiException extends \Exception {

	protected $httpErrorCode;
	protected $httpErrorArray;

	public function __construct($message, $httpErrorCode, $code = 0, Exception $previous = null) {
		$this->httpErrorCode = $httpErrorCode;
		$this->httpErrorArray = $message;
		if(is_array($message))
			$message = $message['error'];

		parent::__construct($message, $code, $previous);
    }

    public function getCompleteErrorArray() {
    	return $this->httpErrorArray();
    }

}

?>