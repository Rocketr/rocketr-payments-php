<?php

namespace RocketrPayments;

class RocketrPaymentsException extends \Exception {

	public function __construct($message, $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous);

    }
}

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