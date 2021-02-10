<?php

/**
 * Donation Product
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Controller\Adminhtml\Product;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\AbstractAction;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use ZoZhang\Donation\Api\ProductRepositoryInterface;

/**
 * Class Delete
 * @package ZoZhang\Donation\Controller\Adminhtml\Product
 */

class Delete extends AbstractAction
{

    /**
     * @var ProductRepositoryInterface
     */
    private $_productRepository;

    /**
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository
    ) {
        $this->_productRepository = $productRepository;

        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $this->_productRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('The product has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a record to delete.'));
        return $resultRedirect->setPath('*/*/');
    }

}
