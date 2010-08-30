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
		if (array_key_exists('action', $this->attributes)) {
			$this->requireResource(
				Ajde_Resource_Local::TYPE_STYLESHEET,
				$this->attributes['action'],
				$this->attributes['format'],
				$this->attributes['base'],
				$this->attributes['position']
			);
		} elseif (array_key_exists('filename', $this->attributes)) {
			$this->requirePublicResource(
				Ajde_Resource_Local::TYPE_STYLESHEET,
				$this->attributes['filename'],
				$this->attributes['position']
			);
		}
		
	}
}