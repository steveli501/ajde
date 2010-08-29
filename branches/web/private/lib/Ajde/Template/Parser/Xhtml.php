<?php	

class Ajde_Template_Parser_Xhtml extends Ajde_Template_Parser
{
	protected $_defaultNS = null;
	protected $_acNS = null;
	
	/**
	 * 
	 * @return Ajde_Template_Parser_Xhtml
	 */
	public static function getInstance()
	{
		static $instance;
		return $instance === null ? $instance = new self : $instance;
	}
	
	public function parse(Ajde_Template $template)
	{
		// Get the XHTML
		$xhtml = $this->_getContents($template->getFullPath());
		$doc = new DOMDocument();
		$doc->registerNodeClass('DOMElement', 'Ajde_Template_Parser_Xhtml_Element');
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true;
		$doc->loadXML($xhtml);
		
		// Get the root element
		/* @var $root DOMNode */
		$root = $doc->documentElement;		
		$this->_defaultNS = $root->lookupNamespaceURI(null);
		$this->_acNS = $root->lookupNamespaceURI(Ajde_Component::AC_XMLNS);
		
		// Ajde_Component processing
		$processed = $this->_process($root);
		
		// Return the inner XML of root element (exclusive)
		$xml = $this->_innerXml($processed);
		
		// Break out the CDATA
		$return = $this->_breakOutCdata($xml);
		
		return $return;
	}
	
	protected function _process(DOMElement $root)
	{
		foreach($root->getElementsByTagNameNS($this->_acNS, '*') as $element) {
			/* @var $element Ajde_Template_Parser_Xhtml_Element */
			if ($element->inACNameSpace()) {
				$element->processComponent();
			}
		}
		return $root;
	}
	
	protected function _breakOutCdata($xml) 
	{
		$patterns = array(
			'%<ac:.+<!\[CDATA\[%',
			'%\]\]>.*</ac:.+>%'
		);
		$return = preg_replace($patterns, '', $xml);
		return $return ;
	}
	
	protected function _innerXml(DOMElement $node)
	{
		$return = '';
		foreach ($node->childNodes as $element) { 
			$return .= $element->ownerDocument->saveXML($element);
		}
		return $return;
	}
}