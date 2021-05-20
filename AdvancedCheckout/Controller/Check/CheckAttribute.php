<?php

namespace Tigren\AdvancedCheckout\Controller\Check;

use Magento\Framework\App\Action\Context;

use Magento\Framework\Controller\Result\JsonFactory;

class CheckAttribute extends \Magento\Framework\App\Action\Action
{
    /**
     *
     */
    protected $resultJsonFactory;
    protected $productRepository;
    protected $cartHelper;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Checkout\Model\Cart $cart
        )
    {
        $this->productRepository = $productRepository;
        $this->cartHelper = $cartHelper;
        $this->cart = $cart;
        $this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);
    }


    public function execute()
    {

        $result = $this->resultJsonFactory->create();

        if ($this->getRequest()->isAjax())
        {
            $productId = $this->getRequest()->getParam('data');
            $product = $this->productRepository->getById($productId);

            if ($product->getResource()->getAttribute('allow_multi_order_attribute')->getFrontend()->getValue($product) == 'No') {
                if ($this->cartHelper->getItemsCount() != 0) {
                    foreach ($this->cart->getQuote()->getAllVisibleItems() as $item) {
                        if($item->getSku() != $product->getSku()) {
                            return $result->setData(true);
                        }
                    }
                }
            }
            return $result->setData(false);
        }
    }
}
