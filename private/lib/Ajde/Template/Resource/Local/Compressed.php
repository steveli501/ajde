<?php

class Ajde_Template_Resource_Local_Compressed extends Ajde_Template_Resource
{
	public function  __construct($type, $filename)
	{
		$this->setFilename($filename);
		parent::__construct($type);		
	}

	/**
	 *
	 * @param string $encodedResource
	 * @return Ajde_Template_Resource
	 */
	public static function fromLinkUrl($encodedResource)
	{
		$resourceArray = unserialize(base64_decode($encodedResource));
		$resource = new self($resourceArray['type'], $resourceArray['filename']);
		return $resource;
	}

	public function getLinkCode()
	{
		ob_start();

		// variables for use in included link template
		$url = $this->getLinkUrl();

		// create temporary resource for link filename
		$linkFilename = Ajde_Template_Resource::getLinkTemplateFilename($this->getType());

		Ajde_Cache::getInstance()->addFile($linkFilename);
		include $linkFilename;

		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	public function getLinkUrl()
	{
		$url = 'resource/compressed/' . $this->getType() . '/';		
		$url .= urlencode(base64_encode(serialize(
				array('type' => $this->getType(), 'filename' => $this->getFilename()))));
		$url .= '/';
		if (Config::get('debug') === true)
		{
			$url .= '?file=' . str_replace('%2F', ':', urlencode($this->getFilename()));
		}
		return $url;
	}

	public function getFilename() {
		return $this->get('filename');
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