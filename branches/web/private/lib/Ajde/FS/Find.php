<?php

class Ajde_FS_Find extends Ajde_Object_Static
{
	public static function findFile($dir, $pattern)
	{
		// Escape for regex pattern
		// TODO: this is way too simplistic
		$regex = '/^' . str_replace('.', '\.', $pattern) . '$/';
		// Asterix wildcard
		$regex = str_replace('*', '.+', $regex);
		foreach (new DirectoryIterator($dir) as $file) {			
			/* @var $file DirectoryIterator */
			if ($file->isFile())
			{
				$fileName = $file->getFilename();
				if (preg_match($regex, $fileName)) {
					return $fileName;
				}
			}
		}
		return false;
	}
}