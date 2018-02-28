<?php

namespace RocketrPayments;

use RocketrPayments\ApiHandler;
use RocketrPayments\RocketrPaymentsException;
use RocketrPaymentsException\RocketrPaymentsApiException;

class ApiHandler {
	
	private $baseUrl;
	private $applicationId;
	private $applicationSecret;
	
	public function __construct($applicationId, $applicationSecret) {
		$this->baseUrl = 'https://api.rocketr.net';
		$this->applicationId =  $applicationId;
		$this->applicationSecret = $applicationSecret;
	}
	
	/**
	 * 
	 * @return [httpStatusCode, response];
	 */
	public function performGetRequest($url, $additionalHeaders = []) {
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');                                                                     
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
													'Authorization: ' . $this->applicationSecret,
													'Application-ID: ' . $this->applicationId,
													'Content-Type: application/json'
												], $additionalHeaders));
			
			$result = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if($httpcode === 0) {
				throw new RocketrPaymentsApiException('Unable to connect to the api', $httpcode);
			}

			$resultArray = json_decode($result, true);

			if(json_last_error() != JSON_ERROR_NONE) {
				throw new RocketrPaymentsApiException('Unable to parse response as JSON', 406);
			} elseif($httpcode > 299 && $httpcode < 600) {
				throw new RocketrPaymentsApiException($resultArray, $httpcode);
			}

			return [$httpcode, $resultArray];
		} catch (Exception $e) {
			throw new RocketrPaymentsException($e->getMessage());
		}
	}
	
	/**
	 * @return [httpStatusCode, response];
	 */
	public function performPostRequest($url, $postFields, $additionalHeaders = []) {
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
													'Authorization: ' . $this->applicationSecret,
													'Application-ID: ' . $this->applicationId,
													'Content-Type: application/json'
												], $additionalHeaders));
			$result = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if($httpcode === 0) {
				throw new RocketrPaymentsApiException('Unable to connect to the api', $httpcode);
			}

			$resultArray = json_decode($result, true);

			if(json_last_error() != JSON_ERROR_NONE) {
				throw new RocketrPaymentsApiException('Unable to parse response as JSON', 406);
			} elseif($httpcode > 299 && $httpcode < 600) {
				throw new RocketrPaymentsApiException($resultArray, $httpcode);
			}

			return [$httpcode, $resultArray];
		} catch (Exception $e) {
			throw new RocketrPaymentsException($e->getMessage());
		}
	}
	
	/**
	 * @return [httpStatusCode, response];
	 */
	private function performUploadAndPostRequest($url, $postFields, $files = [], $additionalHeaders = []) {
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $url);
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$this->curl_custom_postfields($ch, $postFields, $files, array_merge([
													'Authorization: ' . $this->applicationSecret,
													'Application-ID: ' . $this->applicationId,
													'Content-Type: application/x-www-form-urlencoded'
												], $additionalHeaders));
												
			$result = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			if($httpcode === 0) {
				throw new RocketrPaymentsApiException('Unable to connect to the api', $httpcode);
			}

			$resultArray = json_decode($result, true);

			if(json_last_error() != JSON_ERROR_NONE) {
				throw new RocketrPaymentsApiException('Unable to parse response as JSON', 406);
			} elseif($httpcode > 299 && $httpcode < 600) {
				throw new RocketrPaymentsApiException($resultArray, $httpcode);
			}

			return [$httpcode, $result];
		} catch (Exception $e) {
			throw new RocketrPaymentsException($e->getMessage());
		}
	}
	
	/**
	 * Adapted from http://php.net/manual/en/curlfile.construct.php#115160
	 * 
	 * For safe multipart POST request for PHP5.3 ~ PHP 5.4.
	 *
	 * @param resource $ch cURL resource
	 * @param array $assoc "name => value"
	 * @param array $files "name => path"
	 * @return bool
	*/
	private function curl_custom_postfields($ch, $assoc = [], $files = [], $headers = []) {
	   
		// invalid characters for "name" and "filename"
		static $disallow = array("\0", "\"", "\r", "\n");
	   
		// build normal parameters
		foreach ($assoc as $k => $v) {
			$k = str_replace($disallow, "_", $k);
			if(is_array($v)){
				for($i = 0; $i < sizeof($v); $i++) {
					$body[] = implode("\r\n", array(
						"Content-Disposition: form-data; name=\"{$k}[{$i}]\"",
						"",
						filter_var($v[$i]),
					));
				}
			} else {
				$body[] = implode("\r\n", array(
					"Content-Disposition: form-data; name=\"{$k}\"",
					"",
					filter_var($v),
				));
			}
		}
	   
		// build file parameters
		foreach ($files as $k => $v) {
			switch (true) {
				case false === $v = realpath(filter_var($v)):
				case !is_file($v):
				case !is_readable($v):
					continue; // or return false, throw new InvalidArgumentException
			}
			$data = file_get_contents($v);
			
			$k = str_replace($disallow, "_", $k);
			$v = str_replace($disallow, "_", $v);
			$body[] = implode("\r\n", array(
				"Content-Disposition: form-data; name=\"{$k}\"; filename=\"{$v}\"",
				"Content-Type: application/octet-stream",
				"",
				$data,
			));
		}
	   
		// generate safe boundary
		do {
			$boundary = "---------------------" . md5(mt_rand() . microtime());
		} while (preg_grep("/{$boundary}/", $body));
	   
		// add boundary for each parameters
		array_walk($body, function (&$part) use ($boundary) {
			$part = "--{$boundary}\r\n{$part}";
		});
	   
		// add final boundary
		$body[] = "--{$boundary}--";
		$body[] = "";
	   
		// set options
		return @curl_setopt_array($ch, array(
			CURLOPT_POST       => true,
			CURLOPT_POSTFIELDS => implode("\r\n", $body),
			CURLOPT_HTTPHEADER => array_merge(array(
				"Expect: 100-continue",
				"Content-Type: multipart/form-data; boundary={$boundary}", // change Content-Type
			), $headers),
		));
	}
}
?>
