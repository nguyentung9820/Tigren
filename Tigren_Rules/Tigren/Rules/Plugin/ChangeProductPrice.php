<?php
 
namespace Tigren\Rules\Plugin;

use Tigren\Rules\Model\ResourceModel\Rules\CollectionFactory;
 
class ChangeProductPrice
{
	protected $collectionFactory;
    protected $storeManager;

    public function __construct(
       
        \Magento\Customer\Model\Session $customerSession,
        CollectionFactory $collectionFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->customerSession = $customerSession;
        $this->collectionFactory = $collectionFactory;
        $this->timezone = $timezone;
        $this->storeManager = $storeManager;
    }

    public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
        $item = $subject->getSku();
        $customerGroup = $this->customerSession->getCustomer()->getGroupId();
        $store_date = $this->timezone->date()->format('Y/m/d');
        $data = $this->collectionFactory->create();
        $storeId = $this->storeManager->getStore()->getId();
        $data = $data
        ->addFieldToFilter('is_active',['eq' => 1])
        ->addFieldToFilter('start_time', ['lteq' => $store_date])
        ->addFieldToFilter('end_time', ['gteq' => $store_date])
        ->setOrder('sort_order', 'des');
        foreach($data as $key => $value) {
            if (!in_array($customerGroup, explode(',', $value['customer_group_id'])) || !in_array($item, explode(',', $value['product_id'])) || !in_array($storeId, explode(',', $value['store_id']))) {
               	$data->removeItemByKey($key);
            }
        }
        $rule = $data->getFirstItem()->getData();
        if ($rule) {
        	$result = $result - ($result*$rule['discount_amount']/100);
        }
        return $result;  
	}
}