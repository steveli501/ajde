<?php

class Ajde_Template_Parser_Xhtml extends Ajde_Template_Parser
{
	public static function getInstance()
	{
		static $instance;
		return $instance === null ? $instance = new self : $instance;
	}
}