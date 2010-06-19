<?php

class userrpx extends model {
	
	private $_rpxMappings = array();
	private $_userId = null;
	
	function __construct($userId = null) {
		parent::__construct(null);
		if (isset($userId)) {
			$this->_userId = $userId;
			$this->getMappings();
		}
		return $this;
	}
	
	public function getMappings($reset = false) {
		if ((empty($this->_rpxMappings) || $reset === true) && !empty($this->_userId)) {
			return $this->_rpxMappings = $this->sql("SELECT rpx_ident FROM userrpx WHERE user_id = ".(int)$this->_userId);
		} else {
			return $this->_rpxMappings;
		}
	}
	
	public function map($rpxIdent) {
		if (!empty($this->_userId)) {
			$ret = $this->insert(
				array(
					"user_id" => $this->_userId,
					"rpx_ident" => $rpxIdent
				)
			);
			$this->getMappings(true);
			return $ret;
		} else {
			return false;
		}	
	}
	
	public function unMap($rpxIdent) {
		if (!empty($this->_userId)) {
			$ret = $this->sql("DELETE FROM userrpx WHERE user_id = ".(int)$this->_userId." AND rpx_ident = '".mysql_real_escape_string($rpxIdent, parent::getDbLink())."'");
			$this->getMappings(true);
			return $ret;
		} else {
			return false;
		}
	}
	
	public static function hasMap($rpxIdent) {
		return parent::sql("SELECT user_id FROM userrpx WHERE rpx_ident = '".mysql_real_escape_string($rpxIdent, parent::getDbLink())."'");
	}
	
	public static function getMap($rpxIdent) {
		return (int) parent::sqlOne("SELECT user_id FROM userrpx WHERE rpx_ident = '".mysql_real_escape_string($rpxIdent, parent::getDbLink())."'");
	}
	
}

?>