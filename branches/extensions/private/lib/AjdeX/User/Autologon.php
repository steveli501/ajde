<?php

class AjdeX_User_Autologon extends Ajde_Object_Singleton
{	
	public static function getInstance()
	{
       static $instance;
       return $instance === null ? $instance = new self : $instance;
	}
	 
	static public function __bootstrap()
	{
		AjdeX_Model::register('user');
		if (AjdeX_User::getLoggedIn()) {
			return true;
		}		
		$user = new UserModel();
		$user->verifyCookie();
		return true;
	}
}