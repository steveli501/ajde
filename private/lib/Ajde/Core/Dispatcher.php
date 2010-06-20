<?php

class Ajde_Core_Dispatcher extends Ajde_Core_Object_Standard
{
	public function bootstrap()
	{
		$request = Ajde_Http_Request::fromGlobal();
		$this->dispatch($request);
		return true;
	}

	public function dispatch(Ajde_Http_Request $request)
	{
		return false;
	}
}