<?php

namespace Tigren\CustomerGroupCatalog\Controller\Adminhtml\Rule;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context; 
use Tigren\CustomerGroupCatalog\Model\Rule;
use Tigren\CustomerGroupCatalog\Model\RuleFactory;

class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Tigren_CustomerGroupCatalog::save';
    
    /**
     * @var \Tigren\CustomerGroupCatalog\Model\RuleFactory
     */
    var $ruleFactory;
 
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Tigren\CustomerGroupCatalog\Model\RuleFactory $ruleFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Tigren\CustomerGroupCatalog\Model\RuleFactory $ruleFactory
    ) {
        parent::__construct($context);
        $this->ruleFactory = $ruleFactory;
    }
 
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $data['customer_group'] = implode(',', $data['customer_group']);
        $data['store'] = implode(',', $data['store']);
        $data['start_time'] = date('Y-m-d', strtotime($data['start_time']));
        $data['end_time'] = date('Y-m-d', strtotime($data['end_time']));
        // print_r($data);
        // die();
        try {
            $rowData = $this->ruleFactory->create();
            $rowData->setData($data);
            $rowData->save();
             
            var_dump($rowData->getData());
            die();
            $this->messageManager->addSuccess(__('Row data has been successfully saved'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        
        $this->_redirect('*/*/');
    }
}