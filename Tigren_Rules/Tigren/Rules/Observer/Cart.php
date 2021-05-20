<?php

namespace Tigren\Rules\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class Cart
 * @package VendorName\Changeprice\Observer
 */
class Cart implements ObserverInterface
{
    protected $timezone;
    protected $storeManager;
    public function __construct(
        \Tigren\Rules\Model\ResourceModel\Rules\CollectionFactory $collectionFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->timezone = $timezone;
        $this->customerSession = $customerSession;
    }
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $item = $observer->getEvent()->getData('quote_item');
        $product = $observer->getEvent()->getData('product');
        $user_data = $this->customerSession->getData();
        $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
        $cart_product_sku = $item['sku'];
        $price = ''; //item['price']
        $raw_data = $this->collectionFactory->create();
        $store_date = $this->timezone->date()->format('Y/m/d');
        $current_store_id = $item['store_id'];
        $data = $raw_data
            ->addFieldToFilter('is_active', ['eq' => 1])
            ->addFieldToFilter('start_time', ['lteq' => $store_date])
            ->addFieldToFilter('end_time', ['gteq' => $store_date])
            ->setOrder('sort_order', 'des');
        foreach($data as $key) {
            $customer_group_array = explode(',', $key['customer_group_id']);
            $store_array = explode(',', $key['store_id']);
            $product_sku_array = explode(',', $key['product_id']);
            if (in_array($user_data['customer_group_id'],$customer_group_array)) {
                if (in_array($current_store_id,$store_array)) {
                    if (in_array($cart_product_sku,$product_sku_array)) {
                        $price = round(floatval($item['price']*(100-$key['discount_amount'])/100),2);
                        break;
                    }
                }
            }
        }
        // add your logic for custom price
        $item->setOriginalCustomPrice($price);
        $item->setCustomPrice($price);
        $item->getProduct()->setIsSuperMode(true);
    }
}