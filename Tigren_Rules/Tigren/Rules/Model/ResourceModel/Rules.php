<?php
 
namespace Tigren\Rules\Model\ResourceModel;
 
class Rules extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('customer_group_catalog_rules', 'rule_id');
    }
}