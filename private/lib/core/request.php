<?php
class request {
	
	public $module;
	public $action;
	public $format;
	
	private static $get;
	private static $post;
	private static $cookie;
	private static $session;
	private static $flash;
	
	function __construct($request) {
		$request = route::getRoutedRequest($request);
		$elements = array("module", "action", "format");
		$config = config::getInstance();
		foreach($elements as $element) {
			if (array_key_exists($element, $request)) {
				$this->$element = $request[$element];
			} else {
				$this->$element = $config->default[$element];
			}
		}
	}
	
	static function get($var = '*', $from = "get", $default = null) {
		switch ($from) {
			case "get":
				if (!isset(request::$get)) {
					request::$get = $_GET;
				}
				if ($var == '*') {
					$ret = request::$get;
				} else {
					$ret = request::$get[$var];
				}
				break;	
			case "post":	
				if (!isset(request::$post)) {
					request::$post = $_POST;
				}
				if ($var == '*') {
					$ret = request::$post;
				} else {
					$ret = request::$post[$var];
				}
				break;
			case "session":
				if (!isset(request::$session)) {
					session_start();
					request::$session = $_SESSION;
				}
				if ($var == '*') {
					$ret = request::$session;
				} else {
					$ret = request::$session[$var];
				}
				break;
			case "cookie":
				if (!isset(request::$cookie)) {
					request::$cookie = $_COOKIE;
				}
				if ($var == '*') {
					$ret = request::$cookie;
				} else {
					$ret = request::$cookie[$var];
				}
				break;
		}
		if ($ret) {
//			if (is_array($ret)) {
//				array_walk($ret, 'urld');
//			} else {
//				$ret = urldecode($ret);
//			}
			return $ret;
		} else {
			return $default;
		}
	}
	
	static function post($var = '*') {
		return self::get($var, "post");
	}
	
	static function session($var = '*') {
		return self::get($var, "session");
	}
	
	static function cookie($var = '*') {
		return self::get($var, "cookie");
	}
	
	static function set($var, $value, $to = "get") {
		switch ($to) {
			case "get":
				if (!isset(request::$get)) {
					request::$get = $_GET;
				}
				request::$get[$var] = $value;
				break;	
			case "post":	
				if (!isset(request::$post)) {
					request::$post = $_POST;
				}
				request::$post[$var] = $value;
				break;
			case "session":
				if (!isset(request::$session)) {				
					session_start();
					request::$session = $_SESSION;
				}
				request::$session[$var] = $value;
				$_SESSION[$var] = $value;
				break;
			case "cookie":
				if (!isset(request::$cookie)) {				
					request::$cookie = $_COOKIE;
				}
				if (is_array($value)) {
					request::$cookie[$var] = $value;
					// store cookie for 90 days
					foreach($value as $key => $elm) {
						$name = "$var"."[".$key."]";
						setcookie($name, $elm, time()+60*60*24*90);
					}
				} else {
					request::$cookie[$var] = $value;
					// store cookie for 90 days
					setcookie($var, $value, time()+60*60*24*90);
				}
				break;
		}
	}
	
	function destroy($var, $to = "get") {
		switch ($to) {
			case "get":
				unset(self::$get[$var]);
				unset($_GET[$var]);
				break;	
			case "post":	
				unset(self::$post[$var]);
				unset($_POST[$var]);
				break;
			case "session":
				unset(self::$session[$var]);
				unset($_SESSION[$var]);
				break;
			case "cookie":
				unset(self::$cookie[$var]);
				// set cookie with dat in the past
				setcookie($var, null, time()+60*60*24-1);
				break;
		}
		
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
	
}
?>