<?php

namespace Tigren\CustomerGroupCatalog\Model\Rule;
 
use Tigren\CustomerGroupCatalog\Model\ResourceModel\Rule\CollectionFactory;
 
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $_loadedData;
 
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $ruleCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $ruleCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
 
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $rule) {
            $this->_loadedData[$rule->getId()] = $rule->getData();
        }
        return $this->_loadedData;
    }
}