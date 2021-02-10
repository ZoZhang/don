<?php

/**
 * Donation Product
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Helper Data
 * @package ZoZhang\Donation\Helper
 */
class Data extends AbstractHelper
{
    const DONATION_PRODUCT_ENABLED =  'zozhang_donations/product/enabled';

    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * Data constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);

        $this->_storeManager = $storeManager;
    }

    /**
     * get currency symbol
     * @return string
     */
    public function getCurrencySymbol()
    {
        return (string) $this->storeManager->getStore()->getCurrentCurrency()->getCurrencySymbol();
    }

    /**
     * check the module is enabled
     * @return int
     */
    public function isEnabled()
    {
        return (boolean) $this->scopeConfig->getValue(
            self::DONATION_PRODUCT_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

}
