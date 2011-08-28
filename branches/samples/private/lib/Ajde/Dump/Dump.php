<?php

class Ajde_Dump extends Ajde_Object_Static
{
	public static $dump = array();
	
	public static function dump($var) {
		$i = 0;
		$line = null;
		foreach(debug_backtrace() as $item) {			
			$source = sprintf("%s. dumped from <em>%s</em>%s<strong>%s</strong> (line %s)",
				count(self::$dump) + 1,							
				!empty($item['class']) ? $item['class'] : '&lt;unknown class&gt;',
				!empty($item['type']) ? $item['type'] : '::',
				!empty($item['function']) ? $item['function'] : '&lt;unknown function&gt;',
				$line);
			$line = isset($item['line']) ? $item['line'] : null;	
			if ($i == 2) { break; }
			$i++;
		}
		self::$dump[$source] = $var;
	}
	
	public static function getAll() {
		return self::$dump;
	}
}