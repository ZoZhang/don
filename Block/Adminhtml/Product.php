<?php

/**
 * Donation Product
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Block\Adminhtml;

/**
 * Class Product
 * @package ZoZhang\Donation\Block\Adminhtml
 */
class Product extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor.
     *
     * @return null
     */
    public function _construct()
    {
        parent::_construct();

        $this->_controller = 'zozhang_donation';
        $this->_blockGroup = 'ZoZhang_Donation';
        $this->_headerText = 'Donation Product';
        $this->_addButtonLabel = 'Add Product';
    }
}
