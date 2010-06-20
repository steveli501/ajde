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
		error_log(sprintf("PHP error: %s in %s on line %s", $errstr, $errfile, $errline));
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

	public static function handler(Exception $exception)
	{
		if (Config::getInstance()->debug === true)
		{	
			echo self::trace($exception);
		}
		else
		{
			Ajde_Http_Response::redirectServerError();
		}
	}

	public static function trace(Exception $exception)
	{
		if ($exception instanceof ErrorException)
		{
			$type = "PHP Error " . self::getErrorType($exception->getSeverity());
		}
		elseif ($exception instanceof Ajde_Exception)
		{
			$type = "Ajde uncaught exception " . $exception->getCode();
		}
		else
		{
			$type = "Uncaught exception " . $exception->getCode();
		}

		$message = sprintf("%s: <u>%s</u> in <i>%s</i> on line <b>%s</b>",
				$type,
				$exception->getMessage(),
				$exception->getFile(),
				$exception->getLine()				
		);

		$message .= '<ol>';
		foreach($exception->getTrace() as $item)
			$message .= sprintf('<li> <i>%s</i> on line <b>%s</b> calling %s',
					isset($item['file']) ? $item['file'] : '<unknown file>',
					isset($item['line']) ? $item['line'] : '<unknown line>',
					isset($item['function']) ? $item['function'] : '<unknown function>');
		$message .= '</ol>';

		if ($exception instanceof Ajde_Exception && $exception->getCode()) {
			$message .= sprintf('<a href="%s">Ajde documentation on error %s</a>',
				Ajde_Core_Documentation::getUrl($exception->getCode()),
				$exception->getCode()
			);
		}
		return $message;
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