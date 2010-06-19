<?php

class feedcache extends model {
	
	function getFeed($url, $ttl = 30) {
		if ($entry = $this->loadByField("address", $url)) {
			// exist in cache
			if (strtotime($this->expireson) < strtotime(date("Y-m-d"))) {
				// expired
				$result = $this->getLatestFeed($url);
				if ($result) {
					$this->saveToCache($url, $result, $ttl);
				}
				return $result;				
			} else {
				// return cache
				return unserialize( gzuncompress( base64_decode($this->result) ) );
			}
		} else {
			// doesn't exist in cache						
			$result = $this->getLatestFeed($url);
			if ($result && $ttl > 0) {
				$this->saveToCache($url, $result, $ttl);
			}
			return $result;
		}
	}
	
	function getLatestFeed($url) {
		$ret = @file($url);
		if ($ret) {
			return $ret;
		} else {
			return false;
		}
	} 
	
	function saveToCache($address, $result, $ttl) {
		$values = array(
			"address" => $address,
			"result" => base64_encode(gzcompress(serialize($result))),
			"expireson" => date("Y-m-d", strtotime("+".$ttl." days"))
		);
		return $this->replace($values);		
	}
	
}

?>