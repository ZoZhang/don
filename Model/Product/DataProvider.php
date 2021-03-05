<?php

/**
 * Donation Product
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

declare(strict_types=1);

namespace ZoZhang\Donation\Model\Product;

use Magento\Ui\DataProvider\AbstractDataProvider;
use ZoZhang\Donation\Model\ResourceModel\Product\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     * @param \ZoZhang\Donation\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = [],
        CollectionFactory $productCollectionFactory
    ) {
        $this->collection = $productCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = array();
        /** @var Product $product */
        foreach ($items as $product) {
            $this->loadedData[$product->getId()]['product'] = $product->getData();
        }

        return $this->loadedData;
    }
}
