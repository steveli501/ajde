<?php

class Ajde_Session extends Ajde_Object_Standard
{
	function bootstrap()
	{
		session_start();
		return true;
	}
}