<?php
/**
 * Order Item Plugin
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Plugin\Order;

use Magento\Quote\Model\Quote\Item\ToOrderItem as QuoteToOrderItem;
use Magento\Framework\Serialize\Serializer\Json;

class Item
{
    /**
     * @var Json|mixed
     */
    protected $serializer;

    public function __construct(Json $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param QuoteToOrderItem $subject
     * @param \Closure $proceed
     * @param $item
     * @param array $data
     * @return mixed
     */
    public function aroundConvert(QuoteToOrderItem $subject,
                                  \Closure $proceed,
                                  $item,
                                  $data = []
    ) {

        $orderItem = $proceed($item, $data);

        print_r($item->getProductType());

        die;
        $itemAdditionalOptions = $item->getOptionByCode('additional_options');

        $customAdditionalOptions = $itemAdditionalOptions
            ? $this->serializer->unserialize($itemAdditionalOptions->getValue())
            : [];

        if ($customAdditionalOptions) {
            // Get Order Item's other options
            $options = $orderItem->getProductOptions();
            // Set additional options to Order Item
            $options['additional_options'] = $customAdditionalOptions;
            $orderItem->setProductOptions($options);
        }

        return $orderItem;
    }

}
