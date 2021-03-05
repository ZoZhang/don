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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Catalog\Model\ProductRepository as CatalogProductRepository;
use ZoZhang\Donation\Api\Data\ProductInterface;
use ZoZhang\Donation\Api\ProductRepositoryInterface;
use ZoZhang\Donation\Helper\Acl;

class Save extends \Magento\Backend\App\AbstractAction
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = Acl::PRODUCT_SAVE;

    /**
     * @var ProductInterface
     */
    private $_productModel;

    /**
     * @var ProductRepositoryInterface
     */
    private $_productRepository;

    /**
     * @var CatalogProductRepository
     */
    private $_catalogProductRepository;

    /**
     * Save constructor.
     * @param Context $context
     * @param ProductInterface $productModel
     * @param CatalogProductRepository $catalogProductRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Context $context,
        ProductInterface $productModel,
        CatalogProductRepository $catalogProductRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->_productModel = $productModel;
        $this->_productRepository = $productRepository;
        $this->_catalogProductRepository = $catalogProductRepository;

        parent::__construct($context);
    }

    /**
     * Execute method.
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        if (!$this->getRequest()->getParams()) {
            return $this->_redirect('*/*/');
        }
        $data = $this->getRequest()->getParams();
        $id = $this->getRequest()->getParam('entity_id');

        try {
            if ($id) {
                $this->_productModel = $this->_productRepository->getById($id);
            }

            $this->_productModel = $this->validationData($data, $this->_productModel);

            $this->_productRepository->save($this->_productModel);

            $this->messageManager->addSuccessMessage(
                __('The product has been saved.')
            );
            $this->_getSession()->setPageData(false);
            if ($this->getRequest()->getParam('back')) {
                return $this->_redirect(
                    '*/*/edit',
                    ['id' => $this->_productModel->getId()]
                );
            }
        } catch (CouldNotSaveException | LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->_redirect(
                '*/*/edit',
                [
                    'id' => $id
                ]
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->_redirect('*/*/');
    }

    /**
     * @param $data
     * @param $productModel
     * @return mixed
     * @throws LocalizedException
     * @throws NotFoundException
     */
    public function validationData($data, $productModel)
    {
        if (!count($data)) {
            throw new NotFoundException(__('Exception Data.'));
        }

        $validateParams = [ProductInterface::TITLE, ProductInterface::SKU, ProductInterface::AMOUNT, ProductInterface::STORE_IDS, ProductInterface::DESCRIPTION];

        foreach ($validateParams as $paramRequired) {
            if (!array_key_exists($paramRequired, $data)) {
                throw new LocalizedException(__('Exception Data.'));
            }

            if (ProductInterface::STORE_IDS == $paramRequired) {
                $data[$paramRequired] = implode(',', $data[ProductInterface::STORE_IDS]);
            }

            $productModel->setData($paramRequired, $data[$paramRequired]);
        }

        // validation product exists by sku
        $_productModel = $this->_catalogProductRepository->get($data[ProductInterface::SKU]);

        if ($_productModel->getId()) {
            $productModel->setData(ProductInterface::PRODUCT_ID, $_productModel->getId());
        }

        return $productModel;
    }
}
