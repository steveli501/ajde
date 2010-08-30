<?php 

class Ajde_Component_Css extends Ajde_Component_Resource
{
	public static function processStatic(Ajde_Template_Parser $parser, $attributes)
	{
		$instance = new self($parser, $attributes);
		return $instance->process();
	}
	
	public function process()
	{
		$this->requireResource(
			Ajde_Resource_Local::TYPE_STYLESHEET,
			$this->attributes['action'],
			$this->attributes['format'],
			$this->attributes['base']
		);
	}
}