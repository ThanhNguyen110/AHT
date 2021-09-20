<?php

namespace AHT\CancelOrder\Controller\Cancel;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $_order;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Sales\Model\Order $order
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_order = $order;
    }

    public function execute()
    {
        $orderId = $this->getRequest()->getParam('orderid');
        $order = $this->_order->load($orderId);
        if ($order->hasData()) {
            if ($order->canCancel()) {
                $order->cancel();
                $order->save();
                $this->messageManager->addSuccessMessage(__('Order has been canceled successfully.'));
            } else {
                $this->messageManager->addErrorMessage(__('Order cannot be canceled.'));
            }
        } else {
            $this->messageManager->addWarningMessage(__('Order does not exist.'));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
