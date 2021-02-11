<?php

/**
 * Donation Product
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Block\Frontend\Product;

use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Model\ProductFactory;
use Magento\Checkout\Helper\Cart as CartHelper;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use ZoZhang\Donation\Helper\Data as DonationHelper;
use ZoZhang\Donation\Api\ProductRepositoryInterface;

/**
 * Class ListProduct
 * @package ZoZhang\Donation\Block
 */
class Listing extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CartHelper
     */
    protected $cartHelper;

    /**
     * @var ImageBuilder
     */
    private $imageBuilder;

    /**
     * @var DonationHelper
     */
    protected $donationHelper;

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var ProductResource
     */
    protected $productResource;

    /**
     * @var CheckoutSession
     */
    protected $_checkoutSession;

    /**
     * @var array
     */
    protected static $_productModels = [];

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepositoryInterface;

    public function __construct(
        DonationHelper $donationHelper,
        ProductFactory $productFactory,
        ProductResource $productResource,
        CheckoutSession $checkoutSession,
        ProductRepositoryInterface $productRepositoryInterface,
        Context $context,
        CartHelper $cartHelper,
        ImageBuilder $imageBuilder,
        array $data = []
    ) {
        $this->cartHelper = $cartHelper;
        $this->imageBuilder = $imageBuilder;
        $this->donationHelper = $donationHelper;
        $this->productFactory = $productFactory;
        $this->productResource = $productResource;
        $this->_checkoutSession = $checkoutSession;
        $this->productRepositoryInterface = $productRepositoryInterface;

        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * enabled ajax request default
     * @return bool
     */
    public function isAjax()
    {
        return true;
    }

    /**
     * get donation helper class
     * @return DonationHelper
     */
    public function getHelper()
    {
        return $this->donationHelper;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductCollection()
    {
        // load donation product list
        return $this->productRepositoryInterface->getList();
    }

    /**
     * @return mixed
     */
    public function getDonationProductIds()
    {
        return $this->_checkoutSession->getDonationProductIds();
    }

    /**
     * @param $productId
     * @return \Magento\Catalog\Model\Product|mixed
     */
    public function getProduct($productId)
    {
        if (!isset($this->_productModels[$productId])) {
            $product = $this->productFactory->create();
            $this->productResource->load($product, $productId);
            self::$_productModels[$productId] = $product;
        }
        return self::$_productModels[$productId];
    }

    /**
     * @param $productId
     * @param bool $isDeleted
     * @param array $additional
     * @return string
     */
    public function getAddToCartUrl($productId, $isDeleted = false, $additional = [])
    {
        $route = 'donation/cart/' . ($isDeleted ? 'delete' : 'add');

        if ($this->isAjax()) {
            return $this->getUrl($route, ['product' => $productId]);
        }
        $product = $this->getProduct($productId);
        return $this->cartHelper->getAddUrl($product, $additional);
    }

    /**
     * @param $productId
     * @param $imageId
     * @return string
     */
    public function getProductImageUrl($productId, $imageId)
    {
        $product = $this->getProduct($productId);
        return $this->getImage($product, $imageId);
    }

    /**
     * Retrieve product image
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }

    /**
     * Get quote object associated with cart. By default it is current customer session quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuoteData()
    {
        $this->_checkoutSession->getQuote();
        if (!$this->hasData('quote')) {
            $this->setData('quote', $this->_checkoutSession->getQuote());
        }
        return $this->_getData('quote');
    }
}
