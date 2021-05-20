<?php
 
namespace Tigren\Rules\Model;
 
class Rules extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Tigren\Rules\Model\ResourceModel\Rules');
    }
}