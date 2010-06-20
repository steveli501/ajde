<?php

class Ajde_Http_Request extends Ajde_Core_Object_Standard
{
	public static function fromGlobal()
	{
		$request = new self();
		foreach($_GET as $key => $value)
		{
			$request->set($key, $value);
		}
		return $request;
	}

	public function getParam($key) {
		return $this->get($key);
	}

}