<?php

class Ajde_Core_Autoloader
{
	public static function autoloader($className)
	{
	    // Add libraries and config to include path
		$dirs = array(
			PRIVATE_DIR.CLASS_DIR,
			PRIVATE_DIR.CONFIG_DIR
		);

		$files = array();

		// Namespace/Class.php naming
		$files[] = str_ireplace('_', '/', $className) . ".php";

		// Namespace_Class.php naming
		$files[] = $className . ".php";

		// Namespace/Class/Class.php naming
		$classNameArray = explode("_", $className);
		$tail = end($classNameArray);
		$head = implode("/", $classNameArray);
		$files[] = $head . "/" . $tail . ".php";

		foreach ($dirs as $dir)
		{
			foreach (array_unique($files) as $file)
			{
				$path = $dir.$file;
				echo $path."<br>";
				if (file_exists($path)) {
					include_once($path);
					return;
				}
			}

		}

		throw new Ajde_Exception("Unable to load $className");
	}
}