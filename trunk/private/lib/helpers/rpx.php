<?php

class rpx {

	private $_ident = null;
	private $_info = array();

	function __construct($identifier = null) {
		if (isset($identifier)) {
			$this->setIdent($identifier);
		}
	}

	private function setInfo($key, $value) {
		$this->_info[$key] = $value;
	}

	private function hasInfo($key) {
		return isset($this->_info[$key]);
	}

	public function getInfo($key, $subkey = null) {
		if (isset($subkey)) {
			return $this->_info[$key][$subkey];
		} else {
			return $this->_info[$key];
		}
	}

	private function setIdent($ident) {
		$this->_ident = $ident;
	}

	private function hasIdent() {
		return isset($this->_ident);
	}

	public function getIdent() {
		return $this->_ident;
	}

	public function map($userId) {
		if (!$this->hasIdent()) { return false; }
		$userRpx = new userrpx($userId);
		return $userRpx->map($this->getIdent());
	}

	public function unMap($userId) {
		if (!$this->hasIdent()) { return false; }
		$userRpx = new userrpx($userId);
		return $userRpx->unMap($this->getIdent());
	}

	public function hasMap() {
		if (!$this->hasIdent()) { return false; }
		return userRpx::hasMap($this->getIdent());
	}

	public function getUserId() {
		if (!$this->hasIdent()) { return false; }
		return userRpx::getMap($this->getIdent());
	}

	private function _map($rpxIdent, $key) {
		$postData = array(
			'apiKey' 	=> config::getInstance()->rpxApiKey,
			'identifier'=> $rpxIdent,
			'primaryKey'=> $key,
			'format' 	=> 'json'
		);
		$rawJson = curl::getPostRequest('https://rpxnow.com/api/v2/map', $postData);
		return json_decode($rawJson, true);

	}

	public function auth($token) {
		$postData = array(
			'token' 	=> $token,
			'apiKey' 	=> config::getInstance()->rpxApiKey,
			'format' 	=> 'json'
		);
		$rawJson = curl::getPostRequest('https://rpxnow.com/api/v2/auth_info', $postData);
		$authInfo = json_decode($rawJson, true);

		if ($authInfo['stat'] == 'ok') {
			$profile = $authInfo['profile'];
			$this->setIdent($profile['identifier']);
			$this->_info = $profile;
			return $this;
		} else {
			error::log(E_USER_WARNING, "RPX error: ".$authInfo['err']['msg']);
			return false;
		}
	}

}

?>