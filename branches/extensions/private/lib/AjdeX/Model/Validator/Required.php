<?php

class AjdeX_Model_Validator_Required extends AjdeX_Model_ValidatorAbstract
{
	protected function _validate()
	{
		if (empty($this->_value)) {
			if (!$this->getIsAutoIncrement()) {
				return array('valid' => false, 'error' => __('Required field'));
			}
		}
		return array('valid' => true);
	}
}