<?php
namespace Tigren\Rules\Controller\Adminhtml\Manage;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Tigren\Rules\Model\RulesFactory
     */
    var $rulesFactory;
    protected $timezone;
    protected $_filter;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Tigren\Rules\Model\RulesFactory $rulesFactory
     */
    public function __construct(
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Backend\App\Action\Context $context,
        \Tigren\Rules\Model\RulesFactory $rulesFactory
    ) {
        $this->_filter = $filter;
        $this->timezone = $timezone;
        parent::__construct($context);
        $this->rulesFactory = $rulesFactory;
        $this->productCollection = $productCollection;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('rules/manage/addrow');
            return;
        }
        
        try {
            $data['start_time'] = $this->timezone->date(new \DateTime($data['start_time']))->format('Y/m/d');
            $data['end_time'] = $this->timezone->date(new \DateTime($data['end_time']))->format('Y/m/d');

            if (isset($data['customer_group_id']) && count($data['customer_group_id'])>1) {
                $data['customer_group_id'] = implode(',',$data['customer_group_id']);
            } elseif (isset($data['customer_group_id']) && count($data['customer_group_id'])==1) {
                $data['customer_group_id'] = $data['customer_group_id'][0];
            } 
            if (isset($data['store_id']) && count($data['store_id'])>1) {
                $data['store_id'] = implode(',',$data['store_id']);
            } elseif (isset($data['store_id']) && count($data['store_id'])==1) {
                $data['store_id'] = $data['store_id'][0];
            }
            $rowData = $this->rulesFactory->create();
            $rowData->setData($data);
            if (isset($data['rule_id'])) {
                $rowData->setId($data['rule_id']);
            }
            // echo "<pre>";
            // var_dump($data);
            // die();
            $rowData->save();
            $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('rules/manage/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_Rules::save');
    }
}