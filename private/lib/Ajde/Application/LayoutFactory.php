<?php

class Ajde_LayoutFactory extends Ajde_Object_Static
{
	public function __construct($name, $style = 'default', $format = 'html')
	{
		$this->setName($name);
		$this->setStyle($style);

		$base = PRIVATE_DIR.APP_DIR.'layout/'.$this->getName() . '/';
		$action = $this->getStyle();
		$format = $format;
		parent::__construct($base, $action, $format);
	}

	public function setName($name)
	{
		$this->set("name", $name);
	}

	public function setStyle($style)
	{
		$this->set("style", $style);
	}

	public function getName()
	{
		return $this->get('name');
	}

	public function getStyle()
	{
		return $this->get('style');
	}

	public function getFormat()
	{
		return $this->get('format');
	}
}