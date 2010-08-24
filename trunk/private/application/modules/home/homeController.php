<?php

class Home extends Ajde_Controller
{
	function viewDefault()
	{
		Ajde::app()->getResponse()->setRedirect("ajdesite.html");
		return false;
	}
}