<?php

class Ajde_Exception_Handler extends Ajde_Core_Object_Static
{
	public static function bootstrap()
	{
		error_reporting(E_ALL);
		set_error_handler(array('Ajde_Exception_Handler', 'errorHandler'));
		set_exception_handler(array('Ajde_Exception_Handler', 'handler'));
		return true;
	}

	public static function errorHandler($errno, $errstr, $errfile, $errline)
	{
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

	public static function handler(Exception $exception)
	{
		$message = "PHP Uncaught exception: " . $exception->getMessage();
		if (Config::getInstance()->debug === true)
		{	
			echo $message;			
		}
		error_log($message);
	}

	public static function warning($msg)
	{
		html::messageBox($msg, "Warning", "ca5600");
		self::log(error::TYPE_WARNING, $msg);
	}

	public static function fatal($msg)
	{
		html::messageBox($msg, "Error", "800000");
		self::log(error::TYPE_FATAL, $msg);
		die();
	}
	
	public static function getErrorType($type)
	{
		switch ($type)
		{
			case 1: return "E_ERROR";
			case 2: return "E_WARNING";
			case 4: return "E_PARSE";
			case 8: return "E_NOTICE";
			case 16: return "E_CORE_ERROR";
			case 32: return "E_CORE_WARNING";
			case 64: return "E_COMPILE_ERROR";
			case 128: return "E_COMPILE_WARNING";
			case 256: return "E_USER_ERROR";
			case 512: return "E_USER_WARNING";
			case 1024: return "E_USER_NOTICE";
			case 2048: return "E_STRICT";
			case 4096: return "E_RECOVERABLE_ERROR";
			case 8192: return "E_DEPRECATED";
			case 16384: return "E_USER_DEPRECATED";
			case 30719: return "E_ALL";
		}
	}
	
}