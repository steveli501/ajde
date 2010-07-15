<?php

abstract class Ajde_Template_Resource extends Ajde_Object_Standard
{

	const TYPE_JAVASCRIPT	= 'js';
	const TYPE_STYLESHEET	= 'css';
	
	public function  __construct($type)
	{
		$this->setType($type);
	}
	
	abstract public function getFilename();
	abstract protected function getLinkUrl();

	public function getType() {
		return $this->get('type');
	}

	protected function getLinkTemplateFilename()
	{
		$layout = Ajde::app()->getDocument()->getLayout();
		$format = $this->hasFormat() ? $this->getFormat() : 'html';
		return PRIVATE_DIR . LAYOUT_DIR . $layout->getName() . '/link/' . $this->getType() . '.' . $format . '.php';
	}

	public function getLinkCode() {
		ob_start();

		// variables for use in included link template
		$url = $this->getLinkUrl();
		
		Ajde_Cache::getInstance()->addFile($this->getLinkTemplateFilename());
		include $this->getLinkTemplateFilename();
		
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	public function getContentType()
	{
		switch ($this->getType())
		{
			case self::TYPE_STYLESHEET:
				return 'text/css';
				break;
			case self::TYPE_JAVASCRIPT:
				return 'text/javascript';
				break;
		}
	}
}