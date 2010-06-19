<?php
class route {
	
	private static $routes = array(
		"home" => "home/home",
	);
	
	public static function buildRoute($request) {
		$ret = $request["module"];
		if (isset($request["action"])) {
			$ret .= "/" . $request["action"];
			if (isset($request["format"])) {
				$ret .= "/" . $request["format"];
			}
		}
		return $ret;		
	}
	
	public static function buildRequest($route) {
		$elm = explode("/", $route);
		$ret = array("module" => $elm[0]);
		if (isset($elm[1])) {
			$ret["action"] = $elm[1];
			if (isset($elm[2])) {
				$ret["format"] = $elm[2];
			}
		}
		return $ret;
	}
	
	public static function getRoutedRequest($request) {
		$route = self::buildRoute($request);
		if (array_key_exists($route, self::$routes)) {
			return self::buildRequest(self::$routes[$route]);
		} else {
			return $request;
		}
	}
}
?>