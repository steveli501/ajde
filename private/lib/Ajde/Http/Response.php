<?php

class Ajde_Http_Response
{
	public static function redirectNotFound()
	{
		self::dieOnCode("404");
	}

	public static function redirectServerError()
	{
		self::dieOnCode("500");
	}

	public static function dieOnCode($code)
	{
		header("HTTP/1.0 ".$code." ".self::getResponseType($code));
		ob_get_clean();
		header("Status: $code");
		header("Content-type: text/html; charset=UTF-8");
		$_SERVER['REDIRECT_STATUS'] = $code;
		include("errordocument.php");
		die();
	}

	protected static function getResponseType($code)
	{
		switch ($code)
		{
			case 400: return "Bad Request";
			case 401: return "Unauthorized";
			case 403: return "Forbidden";
			case 404: return "Not Found";
			case 500: return "Internal Server Error";
			case 501: return "Not Implemented";
			case 502: return "Bad Gateway";
			case 503: return "Service Unavailable";
			case 504: return "Bad Timeout";
		}
	}

}