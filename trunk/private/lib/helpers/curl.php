<?php

class curl {
	
	static private function rawurlencode_callback($value, $key) {
		return "$key=" . rawurlencode($value);
	}
	
	static public function getPostRequest($url, $postData, $async = false) {
		$encodedVariables = array_map ( array("curl", "rawurlencode_callback"), $postData, array_keys($postData) );
		$postContent = join('&', $encodedVariables);
		$postContentLen = strlen($postContent);
		
		// HTTP Headers moeten gescheiden worden door \r\n, we gebruiken een constante voor betere leesbaarheid.
		define ('CRLF', "\r\n");
		 
		$streamCtx = stream_context_create(
			array (
				'http' => array (
					'method' => 'POST', // dit MOET in hoofdletters
					'content' => $postContent,
					'header'  => "Content-Type: application/x-www-form-urlencoded" . CRLF . "Content-Length: $postContentLen" . CRLF
				)
			)
		);
		if ($async === true) {
			$fp = @fopen($url, 'rb', false, $streamCtx);
     		if (!$fp) { return false; }
     		return true;
		} else {
			// php bug: http://bugs.php.net/bug.php?id=41770
			return @file_get_contents($url, false, $streamCtx);
		}
	}
	
	static public function getAsyncPostRequest($url, $postData) {
		self::getPostRequest($url, $postData, true);
	}
	
}

?>