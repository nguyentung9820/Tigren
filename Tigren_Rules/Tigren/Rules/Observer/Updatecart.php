<?php

namespace Tigren\Rules\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class Updatecart
 * @package VendorName\Changeprice\Observer
 */
class Updatecart implements ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    protected $_checkoutSession;

    /**
     * Updatecart constructor.
     * @param CheckoutSession $checkoutSession
     */
    public function __construct (
        CheckoutSession $checkoutSession
    ) {
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $data = $observer->getEvent()->getData('info');

        $cart = $observer->getEvent()->getData('cart');
        $price ='';

        $convert_data = (array)$data;

        foreach ($convert_data as $itemsdata=>$datainfo) {
            foreach ($datainfo as $itemId => $itemInfo) {
                $item = $this->_checkoutSession->getQuote()->getItemById($itemId);

                if (!$item) {
                    continue;
                }
                // add your logic for custom price
                $item->setOriginalCustomPrice($price);
                $item->setCustomPrice($price);
            }
        }
    }
}