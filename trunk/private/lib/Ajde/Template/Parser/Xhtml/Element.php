<?php	

class Ajde_Template_Parser_Xhtml_Element extends DOMElement
{
	public function inACNameSpace()
	{
		return substr($this->nodeName, 0, 3) === Ajde_Component::AC_XMLNS . ':';	
	}
	
	public function processComponent()
	{
		$component = Ajde_Component::fromNode($this);
		$contents = $component->process();
		/* @var $doc DOMDocument */
		$doc = $this->ownerDocument;
		$cdata = $doc->createCDATASection($contents);
		$this->appendChild($cdata);
	}
	
}