<?php

/**
 * Donation Product
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use ZoZhang\Donation\Api\Data\ProductInterface;

class ProductAction extends Column
{
    const PATH_EDIT = 'zozhang_donation/product/edit';
    const PATH_DELETE = 'zozhang_donation/product/delete';

    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->_urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item[ProductInterface::ID])) {
                    $item[$name]['edit'] = [
                        'href' => $this->_urlBuilder->getUrl(
                            self::PATH_EDIT,
                            ['id' => $item[ProductInterface::ID]]
                        ),
                        'label' => __('Edit')
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->_urlBuilder->getUrl(
                            self::PATH_DELETE,
                            ['id' => $item[ProductInterface::ID]]
                        ),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete') . ' ' . $item[ProductInterface::TITLE],
                            'message' => __(
                                'Are you sure you wan\'t to delete a record?'
                            )
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
