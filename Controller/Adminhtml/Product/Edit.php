<?php

/**
 * Donation Product
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Controller\Adminhtml\Product;

use ZoZhang\Donation\Helper\Acl;
use Magento\Backend\App\AbstractAction;

/**
 * Class Edit
 * @package ZoZhang\Donation\Controller\Adminhtml\Product
 */
class Edit extends AbstractAction
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = Acl::PRODUCT_SAVE;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \ZoZhang\Donation\Model\Product
     */
    private $product;

    /**
     * @var \ZoZhang\Donation\Model\ResourceModel\Product
     */
    private $productResource;

    public function __construct(
        \ZoZhang\Donation\Model\ResourceModel\Product $productResource,
        \Magento\Backend\App\Action\Context $context,
        \ZoZhang\Donation\Model\Product $product,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->product = $product;
        $this->registry = $registry;
        $this->productResource = $productResource;
    }

    /**
     * Execute method.
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        $this->_view->loadLayout();
        $this->_setActiveMenu(
            'ZoZhang_Donation::product_index'
        )->_addBreadcrumb(
            $id
            ? __('Edit Product')
            : __('New Product'),
            $id
            ? __('Edit Product')
            : __('New Product')
        );

        $product = $this->product;
        $this->checkRuleExistAndLoad($id, $product);

        $this->registry->unregister('current_donation_product');
        $this->registry->register('current_donation_product', $product);
        $this->_view->renderLayout();
    }

    /**
     * Check rule exist
     *
     * @param int|null $id
     * @param product $product
     * @return void
     */
    private function checkRuleExistAndLoad($id, $product)
    {
        if ($id) {
            $this->productResource->load($product, $id);

            if (!$product->getId()) {
                $this->messageManager->addErrorMessage(__('This product no longer exists.'));
                $this->_redirect('*/*');
            }
        }

        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $product->getId() ? $product->getName() : __('New Product')
        );

        // set entered data if was error when we do save
        $data = $this->_session->getPageData(true);
        if (!empty($data)) {
            $this->product->addData($data);
        }
    }
}
