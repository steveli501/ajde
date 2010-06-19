<?php
class message {
	
	private static $messages;
	private static $flash;
	private static $debug;
	
	static function setMessage($msg, $pos = "main") {
		self::$messages[$pos] = $msg;
	}
	
	static function getMessage($pos = "main") {
		return self::$messages[$pos];
	}
	
	static function setDebugMsg($msg) {
		self::$debug[] = $msg;
	}
	
	static function getDebugMsg() {
		return self::$debug;
	}
	
	static function setFlash($msg, $pos = "main") {
		self::$flash[$pos] = $msg;
		request::set("_flash", self::$flash, "session");
	}
	
	static function getFlash($pos = "main") {
		if (!isset(self::$flash)) {
			self::$flash = request::get("_flash", "session");
			request::set("_flash", null, "session");
		}
		return self::$flash[$pos];
	}
	
	static function getAll($pos = "main") {
		return self::getMessage($pos) . self::getFlash($pos);
	}
	
}
?>