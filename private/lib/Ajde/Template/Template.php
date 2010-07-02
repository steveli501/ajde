<?php

class Ajde_Template extends Ajde_Object_Standard
{
	public function  __construct($filename)
	{
		$this->setFilename($filename);
	}

	public function setFilename($filename) {
		if (!file_exists($filename)) {
			$exception = new Ajde_Exception(sprintf("Template file %s not
					found", $filename), 90010);
			Ajde::routingError($exception);
		}
		$this->set("filename", $filename);
	}

	public function getContents() {
		ob_start();
		include $this->getFilename();
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}