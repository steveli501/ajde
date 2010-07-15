<?php

class Ajde_Template_Resource_Local extends Ajde_Template_Resource
{
	public function  __construct($type, $base, $action, $format = 'html')
	{
		$this->setBase($base);
		$this->setAction($action);
		$this->setFormat($format);
		parent::__construct($type);
	}

	/**
	 *
	 * @param string $type
	 * @param string $base
	 * @param string $action
	 * @param string $format (optional)
	 * @return Ajde_Template_Resource
	 */
	public static function lazyCreate($type, $base, $action, $format = 'html')
	{
		$filename = self::getFilenameFromStatic($base, $type, $action);
		if (self::exist($filename))
		{
			return new self($type, $base, $action, $format);
		}
		return false;
	}

	/**
	 *
	 * @param string $encodedResource
	 * @return Ajde_Template_Resource
	 */
	public static function fromLinkUrl($encodedResource)
	{
		$resourceArray = unserialize(base64_decode($encodedResource));
		$resource = new self($resourceArray['type'], $resourceArray['base'], $resourceArray['action']);
		return $resource;
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

	protected function getLinkUrl()
	{
		$base = 'resource/' . $this->getType() . '/?';
		$params = '';
		if (Config::get('debug') === true)
		{
			$params .= 'file=' . str_replace('%2F', ':', urlencode($this->getFilename())) . '&';
		}
		$params .= 'r=' . urlencode(base64_encode(serialize(
				array('type' => $this->getType(), 'base' => $this->getBase(), 'action' => $this->getAction()))));
		return $base.$params;
	}

	public function getContents() {
		ob_start();

		Ajde_Cache::getInstance()->addFile($this->getFilename());
		include $this->getFilename();
		
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

}