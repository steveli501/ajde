<?php

class Ajde_Resource_Local_Compressed extends Ajde_Resource
{
	public function  __construct($type, $filename)
	{
		$this->setFilename($filename);
		parent::__construct($type);		
	}

	/**
	 *
	 * @param string $hash
	 * @return Ajde_Resource
	 */
	public static function fromHash($hash)
	{
		$session = new Ajde_Session('_ajde');
		return $session->get($hash);
	}

	public function getLinkCode()
	{
		ob_start();

		// variables for use in included link template
		$url = $this->getLinkUrl();

		// create temporary resource for link filename
		$linkFilename = Ajde_Resource::getLinkTemplateFilename($this->getType());

		Ajde_Cache::getInstance()->addFile($linkFilename);
		include $linkFilename;

		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	public function getLinkUrl()
	{
		
		$hash = md5(serialize($this));
		$session = new Ajde_Session('_ajde');
		$session->set($hash, $this);
		
		$url = 'resource/compressed/' . $this->getType() . '/' . $hash . '/';
		
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