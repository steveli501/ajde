<?php

class Ajde_Core_Bootstrap
{	
	public function init()
	{
		// Configure autoloading
		spl_autoload_register(array("Ajde_Core_Bootstrap", "autoloader"));

		/*
		 * Our bootstrapper calls the bootstrap() methods on all modules defined
		 * in Config::get("bootstrap").
		 */

		foreach(Config::getInstance()->bootstrap as $className)
		{
			if ($className)
			$mode = $className::__getPattern();
			if ($mode === Ajde_Core_Object::OBJECT_PATTERN_STANDARD)
			{
				$instance = new $className;
				$function = array($instance, "bootstrap");
			}
			elseif ($mode === Ajde_Core_Object::OBJECT_PATTERN_SINGLETON)
			{
				$instance = call_user_func($className, "getInstance");
				$function = array($instance, "bootstrap");
			}
			elseif ($mode === Ajde_Core_Object::OBJECT_PATTERN_STATIC)
			{
				$function = "$className::bootstrap";
			}
			elseif ($mode === null || $mode === Ajde_Core_Object::OBJECT_PATTERN_UNDEFINED)
			{
				throw new Ajde_Exception("Type of class $className not
						recognized.");
			}
			try
			{
				if (!method_exists($className, "bootstrap"))
				{
					throw new Ajde_Exception("Bootstrap method in
							$className doesn't exist");
				}
				elseif (!call_user_func($function))
				{
					throw new Ajde_Exception("Bootstrap method in
							$className returned FALSE");
				}
			}
			catch (Exception $e)
			{
				/*
				 * Error handler loaded during bootstrapping, don't use it yet
				 * but rethrow Exception
				 */
				throw $e;
			}
		}

		$r = 1/0;
//		throw new Exception("hoi");
	}

	public static function autoloader($className)
	{

	    // Add libraries and config to include path
		set_include_path(get_include_path().
				PATH_SEPARATOR.PRIVATE_DIR.CLASS_DIR.
				PATH_SEPARATOR.PRIVATE_DIR.CONFIG_DIR);

		$paths = array();

		// Namespace_Class.php naming
		$paths[] = $className . ".php";

		// Namespace/Class.php naming
		$paths[] = str_ireplace('_', '/', $className) . ".php";

		// Namespace/Class/Class.php naming
		$classNameArray = explode("_", $className);
		$tail = end($classNameArray);
		$head = implode("/", $classNameArray);
		$paths[] = $head . "/" . $tail . ".php";
		
		foreach(array_unique($paths) as $path)
		{
			if (@include_once($path))
			{
				return;
			}
		}		
	}
	
}