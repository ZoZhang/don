<?php

/**
 * Donation Product
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Ui\Component\Listing\Column\Product;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\System\Store;
use Magento\Ui\Component\Listing\Columns\Column;
use ZoZhang\Donation\Api\Data\ProductInterface;

class ProductStores extends Column
{
    /**
     * @var Store
     */
    protected $_systemStore;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Store $systemStore
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Store $systemStore,
        array $components = [],
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        /** @var \Magento\Store\Model\Store $store */
        $stores = $this->_systemStore->getStoreCollection();
        if (isset($dataSource['data']['items'])) {
            /** @var ProductInterface $item */
            foreach ($dataSource['data']['items'] as &$item) {
                $ids = explode(",", $item[ProductInterface::STORE_IDS]);
                $storesNames = [];
                if (in_array(0, $ids)) {
                    $storesNames[] = __('All Store View');
                }
                foreach ($stores as $store) {
                    if (in_array(0, $ids)) {
                        break;
                    }
                    if (in_array($store->getId(), $ids)) {
                        $storesNames[] = $store->getName();
                    }
                }
                $item[$this->getName()] = implode('; ', $storesNames) . ';';
            }
        }
        return $dataSource;
    }
}
