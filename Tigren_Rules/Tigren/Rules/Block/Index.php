<?php
 
namespace Tigren\Rules\Block;
 
use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;

class Index extends Template
{
    protected $productCollectionFactory;
    protected $_productRepository;
    protected $storeManager;
    protected $timezone;
    protected $imageHelper;
    protected $productFactory;
    protected $_catalogProductTypeConfigurable;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Tigren\Rules\Model\ResourceModel\Rules\CollectionFactory $collectionFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager, 
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $catalogProductTypeConfigurable,
        Context $context, 
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_productRepository = $productRepository;
        $this->collectionFactory = $collectionFactory;
        $this->timezone = $timezone;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;  
        $this->imageHelper = $imageHelper;
        $this->productFactory = $productFactory;
        $this->_catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
    }

    public function getProductImageUrl($id)
    {
        try 
        {
            $product = $this->productFactory->create()->load($id);
        } 
        catch (NoSuchEntityException $e) 
        {
            return 'Data not found';
        }
        $url = $this->imageHelper->init($product, 'product_base_image')->getUrl();
        return $url;
    }

    public function getSkuFromRules()
    {
        $sku_array = [];
        $user_data = $this->customerSession->getData();
        $raw_data = $this->collectionFactory->create();
        $store_date = $this->timezone->date()->format('Y/m/d');
        $current_store_id = $this->storeManager->getStore()->getId();
        $data = $raw_data
            ->addFieldToFilter('is_active', ['eq' => 1])
            ->addFieldToFilter('start_time', ['lteq' => $store_date])
            ->addFieldToFilter('end_time', ['gteq' => $store_date])
            ->setOrder('sort_order', 'des')
            ->getData();
        foreach($data as $key) {
            $customer_group_array = explode(',', $key['customer_group_id']);
            $store_array = explode(',', $key['store_id']);
            $product_sku_array = explode(',', $key['product_id']);
            if (in_array($user_data['customer_group_id'],$customer_group_array)) {
                if (in_array($current_store_id,$store_array)) {
                    foreach($product_sku_array as $product_sku) {
                        if (!in_array($product_sku,$sku_array)) {
                            array_push($sku_array,$product_sku);
                        }
                    }
                }
            }
        }
        return $sku_array;
    }

    public function getProductBySku($sku)
	{
		return $this->_productRepository->get($sku);
	}

    public function getParentUrl($sku) {
        $id = $this->_productRepository->get($sku);
        $id = $id->getEntityId(); 
        $parentByChild = $this->_catalogProductTypeConfigurable->getParentIdsByChild($id);
        if (isset($parentByChild[0])) {
            //set id as parent product id...
            $id = $parentByChild[0];
        }
        return $this->_productRepository->getById($id)->getProductUrl();
    }

    public function getFinalPrice($sku)
    {
        $price = $this->_productRepository->get($sku)->getPrice();
        $user_data = $this->customerSession->getData();
        $raw_data = $this->collectionFactory->create();
        $store_date = $this->timezone->date()->format('Y/m/d');
        $current_store_id = $this->storeManager->getStore()->getId();
        $data = $raw_data
            ->addFieldToFilter('is_active', ['eq' => 1])
            ->addFieldToFilter('start_time', ['lteq' => $store_date])
            ->addFieldToFilter('end_time', ['gteq' => $store_date])
            ->setOrder('sort_order', 'des')
            ->getData();
        foreach($data as $key) {
            $customer_group_array = explode(',', $key['customer_group_id']);
            $store_array = explode(',', $key['store_id']);
            $product_sku_array = explode(',', $key['product_id']);
            if (in_array($user_data['customer_group_id'],$customer_group_array)) {
                if (in_array($current_store_id,$store_array)) {
                    if (in_array($sku,$product_sku_array)) {
                        $price = floatval($price*(100-$key['discount_amount'])/100);
                        break;
                    }
                }
            }
        }
        return $price;
    }
}

