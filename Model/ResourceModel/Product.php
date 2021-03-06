<?php

namespace ZoZhang\Donation\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Donations
 * @package ZoZhang\Donation\ResourceModel
 */
class Product extends AbstractDb
{
    /**
     * Define resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('donation_product', 'entity_id');
    }
}
