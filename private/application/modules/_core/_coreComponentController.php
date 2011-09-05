<?php

class _coreComponentController extends Ajde_Controller
{
	/************************
	 * Ajde_Component_Resource
	 ************************/
	
	function resourceLocalDefault()
	{
		return $this->_getLocalResource();
	}

	function resourceCompressedDefault()
	{
		return $this->_getCompressedResource();
	}

	protected function _getLocalResource()
	{
		return $this->_getResource('Ajde_Resource_Local');
	}

	protected function _getCompressedResource()
	{
		return $this->_getResource('Ajde_Resource_Local_Compressed');
	}

	protected function _getResource($className)
	{
		// get resource from request
		$hash = Ajde::app()->getRequest()->getParam('id');
		if (!Ajde_Core_Autoloader::exists($className)) {
			throw new Ajde_Controller_Exception("Resource type could not be loaded");
		}
		$resource = call_user_func_array(array($className,"fromHash"), array($hash));
		return $resource->getContents();
	}
	
	/************************
	 * Ajde_Component_Form
	 ************************/

	public function formAjaxDefault()
	{
		$this->setAction('form/ajax');
		$this->getView()->assign('formAction', $this->getFormAction());
		$this->getView()->assign('formId', $this->getFormId());
		$this->getView()->assign('extraClass', $this->getExtraClass());
		$this->getView()->assign('innerXml', $this->getInnerXml());
		return $this->render();
	}

	public function formUploadHtml()
	{
		$this->setAction('form/upload');
		$this->getView()->assign('name', $this->getName());
		$this->getView()->assign('saveDir', $this->getSaveDir());
		$this->getView()->assign('extensions', $this->getExtensions());
		$this->getView()->assign('inputId', $this->getInputId());
		$this->getView()->assign('extraClass', $this->getExtraClass());
		return $this->render();
	}
	
	public function formUploadJson()
	{
		// Load UploadHelper.php
		$helper = new Ajde_Component_Form_UploadHelper();
		
		$saveDir = Ajde::app()->getRequest()->getSaveDir();
		$allowedExtensions = explode(',', Ajde::app()->getRequest()->getExtensions());
		
		// max file size in bytes
		$sizeLimit = 5 * 1024 * 1024;
		
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($saveDir);
		// to pass data through iframe you will need to encode all html tags
		return $result;
	}
	
	/************************
	 * Ajde_Component_Image
	 ************************/
	
	public function imageHtml() {
		$image = $this->getImage();
		$imageId = md5(serialize($image));
		
		$session = new Ajde_Session('AC.Image');
		$session->set($imageId, $image);
				
		$this->setAction('image/show');
		$this->getView()->assign('source', $this->getSource());
		$this->getView()->assign('width', $this->getWidth());
		$this->getView()->assign('height', $this->getHeight());
		$this->getView()->assign('extraClass', $this->getExtraClass());
		return $this->render();
	}
	
	public function imageData() {
		$imageId = Ajde::app()->getRequest()->getId();
		$session = new Ajde_Session('AC.Image');
		$image = $session->get($imageId);
		
		// TODO: add crop/resize option
		$image->crop($image->getHeight(), $image->getWidth());
				
		Ajde::app()->getResponse()->addHeader('Content-Type', $image->getMimeType());
		$output = $image->getImage();
		return $output;
	}
	
	/************************
	 * Ajde_Component_Crud
	 ************************/
	
	public function crudListDefault()
	{
		if (Ajde::app()->getRequest()->has('edit')) {
			return $this->crudEditDefault();			
		} elseif (Ajde::app()->getRequest()->has('new')) {
			return $this->crudEditDefault();
		}
		$this->setAction('crud/list');
		
		$crud = $this->getCrudInstance();
		$crudId = spl_object_hash($crud);
		$options = $this->getCrudOptions();
		
		$items = $crud->getItems();
		$fields = $crud->getFields();
		$labels = $crud->getFieldLabels();
		
		$items->loadParents();
		
		$session = new Ajde_Session('AC.Crud');
		$session->set($crudId, get_class($crud->getModel()));
		
		$this->getView()->assign('id', $crudId);
		$this->getView()->assign('items', $items);
		$this->getView()->assign('fields', $fields);
		$this->getView()->assign('labels', $labels);
		$this->getView()->assign('options', $options);
		return $this->render();
	}
	
	public function crudEditDefault()
	{
		$this->setAction('crud/edit');
		
		$crud = $this->getCrudInstance();
		$crudId = spl_object_hash($crud);
		$options = $this->getCrudOptions();
		
		$id = Ajde::app()->getRequest()->getParam('edit');
		
		$item = $crud->getItem($id);
		$fields = $crud->getFields();
		$labels = $crud->getFieldLabels();
		
		if (!empty($id)) {
			$item->loadParents();
		}
		
		$session = new Ajde_Session('AC.Crud');
		$session->set($crudId, get_class($crud->getModel()));
		
		$this->getView()->assign('id', $crudId);
		$this->getView()->assign('item', $item);
		$this->getView()->assign('fields', $fields);
		$this->getView()->assign('labels', $labels);
		$this->getView()->assign('options', $options);
		return $this->render();
	}
	
	public function crudCommitJson()
	{		
		$operation = Ajde::app()->getRequest()->getParam('operation');
		$crudId = Ajde::app()->getRequest()->getParam('crudId');
		$id = Ajde::app()->getRequest()->getParam('id');
		
		switch ($operation) {
			case 'delete':
				return $this->crudDelete($crudId, $id);
				break;
			case 'save':
				return $this->crudSave($crudId, $id);
				break;
			default:
				return array('operation' => $operation, 'success' => false);
				break;
		}
	}
	
	public function crudDelete($crudId, $id)
	{
		$session = new Ajde_Session('AC.Crud');
		$modelName = $session->get($crudId);
		
		AjdeX_Model::register('*');
		
		$model = new $modelName();
		$model->loadByPK($id);
		$success = $model->delete();
		
		return array('operation' => 'delete', 'success' => $success);
	}
	
	public function crudSave($crudId, $id)
	{
		$session = new Ajde_Session('AC.Crud');
		$modelName = $session->get($crudId);
		
		AjdeX_Model::register('*');
		
		$model = new $modelName();
		
		// Get POST params
		$post = $_POST;
		foreach($post as $key => $value) {
			if (empty($value)) {
				unset($post[$key]);
			}
		}
		$id = issetor($post["id"]);
		
		if (!empty($id)) {
			$model->loadByPK($id);
			$model->populate($post);
			$success = $model->save();
			return array('operation' => 'update', 'success' => $success);
		} else {
			$model->populate($post);
			$success = $model->insert();
			return array('operation' => 'insert', 'success' => $success);
		}
	}
}