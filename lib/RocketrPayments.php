<?php

namespace RocketrPayments;

class RocketrPayments {
	
	//@var String | Application ID
	public static $applicationId;
	
	//@var String | Application Secret
	public static $applicationSecret;

	//@var ApiHandler | Used to make the requests
	public static $apiHandler;

	/**
	 * Sets the API credentials to be used
	 *
	 * @param string $applicationId
	 * @param string $applicationSecret
	 */
	public static function setApiKey($applicationId, $applicationSecret) {
		self::$applicationId = $applicationId;
		self::$applicationSecret = $applicationSecret;
		self::$apiHandler = new ApiHandler($applicationId, $applicationSecret);
	}


	/**
	 * Returns the API Handler
	 *
	 * @throws RocketrPaymentsException
	 */
	public static function getApiHandler() {
		if(!isset(self::$apiHandler)) {
			if(!isset(self::$applicationId) || !isset(self::$applicationSecret))
				throw new RocketrPaymentsException('API Credentials not set');
			
			self::$apiHandler = new ApiHandler(self::$applicationId, self::$applicationSecret);
			return self::$apiHandler;
		} else {
			return self::$apiHandler;
		}
	}
}

?>