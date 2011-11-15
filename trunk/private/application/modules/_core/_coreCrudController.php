<?php

/**
 * Default to extend AjdeX_Acl_Controller for enhanced security
 */
class _coreCrudController extends AjdeX_Acl_Controller
{
	/************************
	 * Ajde_Component_Crud
	 ************************/
	
	public function listDefault()
	{
		$cache = Ajde_Cache::getInstance();
		$cache->disable();
		
		if (Ajde::app()->getRequest()->has('edit') || Ajde::app()->getRequest()->has('new')) {
			return $this->editDefault();			
		}
		
		$crud = $this->getCrudInstance();
		
		$session = new Ajde_Session('AC.Crud');
		$session->setModel($crud->getHash(), $crud);
		
		$view = $crud->getTemplate();		
		$view->assign('crud', $crud);
		
		return $view->render();
	}
	
	public function editDefault()
	{
		/* @var $crud AjdeX_Crud */
		$this->setAction('edit');
		
		$crud = $this->getCrudInstance();
		$crud->setAction('edit');
		
		if (!$crud->hasId()) {
			$crud->setId(Ajde::app()->getRequest()->getParam('edit', false));
		}
			
		$session = new Ajde_Session('AC.Crud');
		$session->setModel($crud->getHash(), $crud);
		
		$view = $crud->getTemplate();		
		$view->requireJs('component/shortcut');
		$view->assign('crud', $crud);
		
		return $view->render();
	}
	
	public function commitJson()
	{
		$operation = Ajde::app()->getRequest()->getParam('operation');
		$crudId = Ajde::app()->getRequest()->getParam('crudId');
		$id = Ajde::app()->getRequest()->getPostParam('id');
		
		AjdeX_Model::registerAll();
		
		switch ($operation) {
			case 'delete':
				return $this->delete($crudId, $id);
				break;
			case 'save':
				return $this->save($crudId, $id);
				break;
			default:
				return array('operation' => $operation, 'success' => false);
				break;
		}
	}
	
	public function delete($crudId, $id)
	{
		$session = new Ajde_Session('AC.Crud');
		$crud = $session->getModel($crudId);
		$model = $crud->getModel();
				
		$model->loadByPK($id);
		$success = $model->delete();
		
		return array('operation' => 'delete', 'success' => $success);
	}
	
	public function save($crudId, $id)
	{
		$session = new Ajde_Session('AC.Crud');
		$crud = $session->getModel($crudId);
		/* @var $model AjdeX_Model */
		$model = $crud->getModel();
				
		// Get POST params
		$post = $_POST;
		foreach($post as $key => $value) {
			if (empty($value)) {
				unset($post[$key]);
			}
		}
		$id = issetor($post["id"]);
		$operation = empty($id) ? 'insert' : 'save';
		
		if ($operation === 'save') {
			$model->loadByPK($id);
		}
		$model->populate($post);
		if (!$model->validate()) {
			return array('operation' => $operation, 'success' => false, 'errors' => $model->getValidationErrors());
		}
		$success = $model->{$operation}();
		if ($success === true) {
			$session->destroy();
		}
		return array('operation' => $operation, 'success' => $success);
	}
}