<?php

/**
 * Donation Product
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Controller\Adminhtml\Product;

/**
 * Class NewAction
 * @package ZoZhang\Donation\Controller\Adminhtml\Product
 */
class NewAction extends \Magento\Backend\App\AbstractAction
{
    /**
     * Execute method.
     *
     * @return null
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
