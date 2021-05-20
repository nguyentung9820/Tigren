<?php
 
namespace Tigren\Rules\Model\ResourceModel\Rules;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'rule_id';
 
    protected function _construct()
    {
        $this->_init('Tigren\Rules\Model\Rules', 'Tigren\Rules\Model\ResourceModel\Rules');
    }
}
