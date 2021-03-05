<?php
/**
 * Custom Price Events
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Observer\Checkout;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Cart as CheckoutCart;
use Magento\Quote\Model\QuoteRepository;
use Magento\Checkout\Model\Session as CheckoutSession;
use ZoZhang\Donation\Api\ProductRepositoryInterface;

/**
 * Class Price
 * @package ZoZhang\Donation\Observer
 */
class Cart implements ObserverInterface
{
    /**
     * @var CheckoutCart
     */
    protected $_cart;

    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepositoryInterface;

    /**
     * @var QuoteRepository
     */
    protected $_quoteRepository;

    /**
     * @var CheckoutSession
     */
    protected $_checkoutSession;


    public function __construct(
        CheckoutCart $cart,
        QuoteRepository $quoteRepository,
        CheckoutSession $checkoutSession,
        ProductRepositoryInterface $productRepositoryInterface
    )
    {
        $this->_cart = $cart;
        $this->_checkoutSession = $checkoutSession;
        $this->_quoteRepository = $quoteRepository;
        $this->_productRepositoryInterface = $productRepositoryInterface;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductCollection()
    {
        // load donation product list
        return $this->_productRepositoryInterface->getList();
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $donationItems = $this->getProductCollection();

        if (!count($donationItems)) {
            return;
        }

        $checkoutSessionDonations = [];
        $quoteId = $this->_cart->getQuote()->getId();
        $quoteRepository =  $this->_quoteRepository->getActive($quoteId);
        $quoteItems = $quoteRepository->getAllVisibleItems();

        foreach ($donationItems as $donationItem) {
            foreach($quoteItems as $quoteItem) {
                if ($quoteItem->getProduct()->getId() == $donationItem->getProductId()) {
                    $quoteItem->getProduct()->setName($donationItem->getTitle());

                    if (!in_array($donationItem->getProductId(), $checkoutSessionDonations)) {
                        $checkoutSessionDonations[] = $donationItem->getProductId();
                    }
                }
            }
        }

        $this->_checkoutSession->setDonationProductIds($checkoutSessionDonations);
    }

}
