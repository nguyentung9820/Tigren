<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\Rules\Pricing\Render;

use Magento\Catalog\Pricing\Price;
use Magento\Framework\Pricing\Render;
use Magento\Framework\Pricing\Render\PriceBox as BasePriceBox;
use Magento\Msrp\Pricing\Price\MsrpPrice;
class FinalPriceBox extends \Magento\Catalog\Pricing\Render\FinalPriceBox
{
    protected $timezone;
    protected $storeManager;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Pricing\SaleableInterface $saleableItem,
        \Magento\Framework\Pricing\Price\PriceInterface $price,
        \Magento\Framework\Pricing\Render\RendererPool $rendererPool,
        \Tigren\Rules\Model\ResourceModel\Rules\CollectionFactory $collectionFactory,
        array $data = [],
        \Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface $salableResolver = null,
        \Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface $minimalPriceCalculator = null,
        \Magento\Customer\Model\SessionFactory $customerSessionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product $resourceProduct,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $resourceConfigurable
    ) {
        $this->_customerSessionFactory = $customerSessionFactory;
        $this->customerSession = $customerSession;
        $this->collectionFactory = $collectionFactory;
        $this->timezone = $timezone;
        $this->storeManager = $storeManager;
        $this->resourceProduct = $resourceProduct;
        $this->resourceConfigurable = $resourceConfigurable;
        parent::__construct($context, 
                            $saleableItem, 
                            $price, 
                            $rendererPool, 
                            $data, 
                            $salableResolver, 
                            $minimalPriceCalculator);
    }
    
    protected function wrapResult($html)
    {
        $isLoggedIn = $this->_customerSessionFactory->create()->isLoggedIn();
        $raw_data = $this->collectionFactory->create();
        $store_date = $this->timezone->date()->format('Y/m/d');
        $storeId = $this->storeManager->getStore()->getId();
        
        $childId = $this->resourceProduct->getIdBySku($this->getSaleableItem()->getSku());
        if ($childId) {
            $parentIds = $this->resourceConfigurable->getParentIdsByChild($childId);
            if (!empty($parentIds)) {
                $skus = $this->resourceProduct->getProductsSku($parentIds);
                echo '<pre>';
                print_r($skus);
                die();
            }
    
        }

        $data = $raw_data
                ->addFieldToFilter('is_active', ['eq' => 1])
                ->addFieldToFilter('start_time', ['lteq' => $store_date])
                ->addFieldToFilter('end_time', ['gteq' => $store_date])
                ->setOrder('sort_order', 'des')
                ->getData();
        $user_data = $this->customerSession->getData();
        // echo "<pre>";
        // var_dump($this->getSaleableItem()->getPriceInfo()->getPrice('msrp_price'));
        // die();
        if($isLoggedIn){
            foreach($data as $key) {
                $customer_group_array = explode(',', $key['customer_group_id']);
                $store_array = explode(',', $key['store_id']);
                $product_sku_array = explode(',', $key['product_id']);
                if (in_array($user_data['customer_group_id'],$customer_group_array)) {
                    if (in_array($storeId,$store_array)) {
                        if (in_array($this->getSaleableItem()->getSku(),$product_sku_array)) {
                            return '<div class="price-box ' . $this->getData('css_classes') . '" ' .
                            'data-role="priceBox" ' .
                            'data-product-id="' . $this->getSaleableItem()->getId() . '"' .
                            '>' . '<del>' . $html . '</del><sup>- ' . round($key['discount_amount']) . '%</sup><b style="font-size: 2rem;"> &nbsp;&nbsp;&nbsp;&nbsp; $' . round(floatval($this->getSaleableItem()->getPrice()*(100-$key['discount_amount'])/100),2) . '</b></div>';
                        }
                    }
                }
            }
            return '<div class="price-box ' . $this->getData('css_classes') . '" ' .
                'data-role="priceBox" ' .
                'data-product-id="' . $this->getSaleableItem()->getId() . '"' .
                '>' . $html. '</div>';
        } else {
            $wording = 'Please Login To See Price';
            return '<div class="" ' .
                'data-role="priceBox" ' .
                'data-product-id="' . $this->getSaleableItem()->getId() . '"' .
                '>'.$wording.'</div>';
        }
    }
}