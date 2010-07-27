<?php

class Home extends Ajde_Controller
{
	function viewDefault()
	{
		Ajde::app()->getResponse()->setRedirect("guestbook.html");
		return false;
	}

	function menuDefault()
	{
		return $this->loadTemplate();
	}
	
}