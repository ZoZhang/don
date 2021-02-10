<?php

namespace ZoZhang\Donation\Model\ResourceModel\Product;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use ZoZhang\Donation\Model\Product as Model;
use ZoZhang\Donation\Model\ResourceModel\Product as ResourceModel;

/**
 * Class Collection
 * @package ZoZhang\Donation\ResourceModel
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @var string
     */
    protected $_eventObject = 'product_collection';

    /**
     * @var string
     */
    protected $_eventPrefix = 'zozhang_donation_product_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
