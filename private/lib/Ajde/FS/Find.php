<?php

class Ajde_FS_Find extends Ajde_Object_Static
{
	public static function findFile($dir, $pattern)
	{
		$search = $dir . $pattern;
		foreach (glob($search) as $filename) {
			return $filename;
		}
		return false;
	}
}