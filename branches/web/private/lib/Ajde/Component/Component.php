<?php 

abstract class Ajde_Component extends Ajde_Object_Standard
{
	const AC_XMLNS = 'ac';
	
	public $attributes = array();
	
	public function __construct($attributes)
	{
		$this->attributes = $attributes;	
	} 
	
	/**
	 * 
	 * @param DOMNode $node
	 * @return Ajde_Component
	 */
	public static function fromNode(DOMNode $node)
	{
		$componentName = ucfirst(str_replace(self::AC_XMLNS . ':', '', $node->nodeName));
		$className = 'Ajde_Component_' . $componentName;
		$nodeAttributes = $node->attributes;
		$attributes = array();
		foreach ($nodeAttributes as $attribute) {
			$attributes[$attribute->name] = $attribute->value;
		}
		return new $className($attributes);
	}
	
	abstract public function process();
}