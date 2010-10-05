<?php 

class Home extends Ajde_Controller
{	
	function viewDefault()
	{
		return $this->loadTemplate();
	}
	
	function zendDefault()
	{
		$date = new Zend_Date();
//		$app = new Zend_Application('live');
//		$app->run();
		
		$view = $this->getView();		
		$view->assign('date', $date);		
		return $view->render();
	}
}
