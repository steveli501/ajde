<?php

class Ajde_Session extends Ajde_Object_Standard
{
	function bootstrap()
	{
		session_cache_limiter('private_no_expire');
		session_start();
		// remove cache headers invoked by session_start();
		header_remove();
		return true;
	}
}