<?php

namespace Tigren\CustomerGroupCatalog\Model\ResourceModel\Rule;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	
	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Tigren\CustomerGroupCatalog\Model\Rule',
			'Tigren\CustomerGroupCatalog\Model\ResourceModel\Rule');
	}

}