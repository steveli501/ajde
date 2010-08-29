<?php 

class Ajde_Component_Js extends Ajde_Component
{
	public static function processStatic($attributes)
	{
		$instance = new self($attributes);
		return $instance->process();
	}
	
	public function process()
	{
		if (!array_key_exists('library', $this->attributes) || !array_key_exists('version', $this->attributes)) {
			throw new Ajde_Component_Exception();
		}
		$url = Ajde_Resource_JsLibrary::getUrl($this->attributes['library'], $this->attributes['version']);
		$resource = new Ajde_Resource_Remote(Ajde_Resource::TYPE_JAVASCRIPT, $url);
		Ajde::app()->getDocument()->addResource($resource, Ajde_Document_Format_Html::RESOURCE_POSITION_FIRST);
	}
}