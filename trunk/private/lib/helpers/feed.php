<?php

class feed {
	
	function getIdFromUrl($url) {
		$ispos = strrpos($url, "/");
		$ispos++;		
		$videohash = substr($url, $ispos, strlen($url)-$ispos);
		return $videohash;
	}
	
	/**
	 * loadFeed
	 *
	 * @param string $url
	 * @return SimpleXMLElement
	 */
	function loadFeed($url, $ttl = 30, $registerXPathNamespace = true) {
		$cache = new feedcache();
		$lines = $cache->getFeed($url, $ttl);
		if ($lines) {
			foreach ($lines as $line) {
			    $xmlresult .= $line . "\n";
			}		
			// load into xml	
			$xml = new SimpleXMLElement($xmlresult);
			if ($registerXPathNamespace) {
				self::registerDefaultNamespace($xml);
			}
			return $xml;
		} else {
			return false;
		}		
	}
	
	/**
	 * loadFeed
	 *
	 * @param string $url
	 * @return string
	 */
	function loadRaw($url, $ttl = 30) {
		$cache = new feedcache();
		$lines = $cache->getFeed($url, $ttl);
		if ($lines) {
			foreach ($lines as $line) {
			    $raw .= $line . "\n";
			}
			return $raw;
		} else {
			return false;
		}		
	}
	
	function registerDefaultNamespace(SimpleXMLElement &$xml) {
		$xml->registerXPathNamespace('default', 'http://www.w3.org/2005/Atom');
	}
	
	function getXPath(SimpleXMLElement &$xml, $xpath) {
		$res = $xml->xpath($xpath);
		$res = (string) $res[0];
		return $res;
	}
	
	/**
	 * getXPathValue
	 * @deprecated 
	 */
	function getXPathValue(SimpleXMLElement &$xml, $xpath) {
		$res = $xml->xpath($xpath);
		$res = (string) $res[0];
		return $res;
	}
	
	/**
	 * getXPathAttr
	 * @deprecated 
	 */
	function getXPathAttr(SimpleXMLElement &$xml, $xpath, $attr) {
		$res = $xml->xpath($xpath);
		if ($res[0]) {
			$res = $res[0]->attributes();
			$res = (string) $res->$attr;
			return $res;
		} else {
			return false;
		}
	}
	
}

?>