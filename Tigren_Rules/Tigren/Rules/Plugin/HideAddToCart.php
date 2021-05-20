<?php

namespace Tigren\Rules\Plugin;

use Magento\Catalog\Model\Product;

class HideAddToCart
{
    protected $_customerSessionFactory;

    public function __construct(
        \Magento\Customer\Model\SessionFactory $customerSessionFactory
    ) {
        $this->_customerSessionFactory = $customerSessionFactory;
    }
    public function afterIsSaleable(Product $product, $isSaleable)
    {
        $customer_data = $this->_customerSessionFactory->create();
        if($customer_data->isLoggedIn()) {
            return $product->isSalable();
        } else {
            return false;
        }
    }
}