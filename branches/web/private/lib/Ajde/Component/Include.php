<?php 

class Ajde_Component_Include extends Ajde_Component
{
	public static function processStatic($attributes)
	{
		$instance = new self($attributes);
		return $instance->process();
	}
	
	public function process()
	{
		if (!array_key_exists('route', $this->attributes)) {
			throw new Ajde_Component_Exception();
		}
		return Ajde_Controller::fromRoute(new Ajde_Core_Route($this->attributes['route']))->invoke();
	}
}