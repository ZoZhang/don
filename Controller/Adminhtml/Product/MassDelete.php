<?php

/**
 * Donation Product
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Ui\Component\MassAction\Filter;
use ZoZhang\Donation\Api\ProductRepositoryInterface;
use ZoZhang\Donation\Model\ResourceModel\Product\CollectionFactory;

/**
 * Class MassDelete
 * @package ZoZhang\Donation\Controller\Adminhtml\Product
 */
class MassDelete extends Action
{
    const ADMIN_RESOURCE = 'ZoZhang_Donation::product_delete';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $_productRepository;

    public function __construct(
        Action\Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->_productRepository = $productRepository;

        parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            throw new NotFoundException(__('Page not found'));
        }

        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $productDeleted = 0;
            foreach ($collection->getItems() as $product) {
                $this->_productRepository->delete($product);

                $productDeleted++;
            }

            if ($productDeleted) {
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been deleted.', $productDeleted)
                );
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(
                $e->getMessage()
            );
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('zozhang_donation/product/index');
    }
}
