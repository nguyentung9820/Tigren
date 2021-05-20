<?php

namespace Tigren\CustomerGroupCatalog\Model;

class Rule extends \Magento\Framework\Model\AbstractModel
{
	protected function _construct()
	{
		$this->_init('Tigren\CustomerGroupCatalog\Model\ResourceModel\Rule');
	}
}