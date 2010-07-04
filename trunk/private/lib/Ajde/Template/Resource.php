<?php

class Ajde_Template_Resource extends Ajde_Object_Standard
{
	public function  __construct($type, $base, $action, $format = 'html')
	{
		$this->setBase($base);
		$this->setAction($action);
		$this->setFormat($format);
		$this->setType($type);
	}

	public static function lazyCreate($type, $base, $action, $format = 'html')
	{
		$filename = self::getFilenameFromStatic($base, $type, $action);
		if (self::exist($filename))
		{
			return new self($type, $base, $action, $format);
		}
		return false;
	}

	public function getBase() {
		return $this->get('base');
	}

	public function getAction() {
		return $this->get('action');
	}

	public function getFormat() {
		return $this->get('format');
	}

	public function getType() {
		return $this->get('type');
	}

	protected static function exist($filename)
	{
		if (file_exists($filename)) {
			return true;
		}
		return false;

	}

	protected static function _getFilename($base, $type, $action)
	{
		return $base . 'res/' . $type . '/' . $action . '.' . $type;
	}

	public function getFilename()
	{
		return $this->_getFilename($this->getBase(), $this->getType(), $this->getAction());
	}

	public static function getFilenameFromStatic($base, $type, $action)
	{
		return self::_getFilename($base, $type, $action);
	}

	protected function getLinkTemplateFilename()
	{
		$layout = Ajde::app()->getDocument()->getLayout();
		return PRIVATE_DIR . LAYOUT_DIR . $layout->getName() . '/link/' . $this->getType() . '.' . $this->getFormat() . '.php';
	}

	protected function getLinkUrl()
	{
		return 'resource/' . $this->getType() . '/?r=' . urlencode(base64_encode(serialize(
				array('type' => $this->getType(), 'base' => $this->getBase(), 'action' => $this->getAction()))));
	}

	public function getContents() {
		ob_start();
		include $this->getFilename();
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	public function getLinkCode() {
		ob_start();

		// vars for use in link template
		$url = $this->getLinkUrl();

		include $this->getLinkTemplateFilename();
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}