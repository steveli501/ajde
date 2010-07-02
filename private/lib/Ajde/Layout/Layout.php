<?php

class Ajde_Layout extends Ajde_Template
{
	public function __construct($name, $style = 'default', $format = 'html')
	{
		$this->setName($name);
		$this->setStyle($style);
		$filename = PRIVATE_DIR.'layout/'.$this->getName().'/template/'.$this->getStyle().'.'.$format.'.php';
		parent::__construct($filename);
	}

	public function setName($name)
	{
		$this->set("name", $name);
	}

	public function setStyle($style)
	{
		$this->set("style", $style);
	}

	public function setBody($contents)
	{
		$this->set("body", $contents);
	}

	public function getBody()
	{
		$this->get("body");
	}
}