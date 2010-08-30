<?php

class Ajde_Template extends Ajde_Object_Standard
{
	public $_contents = null;
	
	public function  __construct($base, $action, $format = 'html')
	{
		$this->setBase($base);
		$this->setAction($action);
		$this->setFormat($format);
		
		if (($fileInfo = $this->_getFileInfo()) === false) {
			$exception = new Ajde_Exception(sprintf("Template file in %s,
					for action %s with format %s not found",
					$base, $action, $format), 90010);
			Ajde::routingError($exception);
		}		
		$className = 'Ajde_Template_Parser_' . $fileInfo['parser'];
		$parser = new $className($this);
		
		$this->setFilename($fileInfo['filename']);
		$this->setParser($parser);
	}
	
	protected function _getFileInfo()
	{
		// go see what templates are available
		$fileNamePatterns = array(
			$this->getAction() . '.' . $this->getFormat(), $this->getAction()
		);
		$fileTypes = array(
			'phtml' => 'Phtml', 'xhtml' => 'Xhtml'
		);
		$fileName = null;
		foreach($fileNamePatterns as $fileNamePattern) {
			foreach($fileTypes as $fileType => $parserType) {
				$filePattern = $fileNamePattern . '.' . $fileType;
				if ($fileMatch = Ajde_FS_Find::findFile($this->getBase().TEMPLATE_DIR, $filePattern)) {
					return array('filename' => $fileMatch, 'parser' => $parserType);
				}				
			}
		}
		return false;		
	}
	
	public function setParser(Ajde_Template_Parser $parser)
	{
		$this->set('parser', $parser);
	}
	
	/**
	 * 
	 * @return Ajde_Template_Parser 
	 */
	public function getParser()
	{
		return $this->get('parser');
	}

	public function exist()
	{
		// since files are checked in constructor, this is not needed anymore?
		throw new Ajde_Core_Exception_Deprecated();
		return file_exists($this->getFullPath());
	}
	
	public function setFilename($filename)
	{
		$this->set('filename', $filename);
		$this->setFullPath();
	}
	
	public function setFullPath()
	{
		$fullPath = $this->getBase() . TEMPLATE_DIR . $this->getFilename();
		return $this->set("fullPath", $fullPath);
	}
	
	public function getFullPath()
	{
		return $this->get("fullPath");
	}

	public function getFilename()
	{
		return $this->get("filename");
	}

	public function getBase()
	{
		return $this->get('base');
	}

	public function getAction()
	{
		return $this->get('action');
	}

	public function getFormat()
	{
		return $this->get('format');
	}

	public function getContents()
	{
		if (!isset($this->_contents))
		{
			Ajde_Event::trigger($this, 'beforeGetContents');
			Ajde_Cache::getInstance()->addFile($this->getFullPath());
			$contents = $this->getParser()->parse($this);		
			$this->setContents($contents);			
			Ajde_Event::trigger($this, 'afterGetContents');
		}
		return $this->_contents;
	}

	public function setContents($contents)
	{
		$this->_contents = $contents;
	}
	
	public function getDefaultResourcePosition()
	{
		return Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT;
	}
}