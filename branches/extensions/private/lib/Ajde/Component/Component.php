<?php 

abstract class Ajde_Component extends Ajde_Object_Standard
{
	const AC_XMLNS = 'ac';
	const AV_XMLNS = 'av';
	
	public $attributes = array();
	protected $_parseRules = array();
	
	/**
	 * 
	 * @var Ajde_Template_Parser
	 */
	protected $_parser = null;
	
	/**
	 * 
	 * @param Ajde_Template_Parser $parser
	 * @param array $attributes
	 */
	public function __construct(Ajde_Template_Parser $parser, $attributes)
	{
		$this->attributes = $attributes;
		$this->_parser = $parser;
		$this->_parseRules = $this->_init();
	} 
	
	/**
	 * 
	 * @return Ajde_Template_Parser
	 */
	public function getParser()
	{
		return $this->_parser;
	}
	
	/**
	 * 
	 * @param DOMNode $node
	 * @return Ajde_Component
	 */
	public static function fromNode(Ajde_Template_Parser $parser, DOMNode $node)
	{
		$componentName = ucfirst(str_replace(self::AC_XMLNS . ':', '', $node->nodeName));
		$className = 'Ajde_Component_' . $componentName;
		$nodeAttributes = $node->attributes;
		$attributes = array();
		foreach ($nodeAttributes as $attribute) {
			$attributes[$attribute->name] = $attribute->value;
		}
		return new $className($parser, $attributes);
	}
	
	abstract public function process();
	
	protected function _init()
	{
		return array();
	}
	
	protected function _attributeParse()
	{
		foreach($this->_parseRules as $attributeSet => $rule) {
			if (array_key_exists($attributeSet, $this->attributes)) {
				return $rule;
			}
		}	
	}
}