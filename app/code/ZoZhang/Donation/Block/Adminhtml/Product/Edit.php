<?php

/**
 * Donation Product
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Block\Adminhtml\Product;

use ZoZhang\Donation\Helper\Acl;
use ZoZhang\Donation\Block\Adminhtml\AbstractGeneric;
use Magento\Backend\Block\Widget\Form\Container;

/**
 * Class Edit
 * @package ZoZhang\Donation\Block
 */
class Edit extends Container
{
    /**
     * Edit constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_addButtons();

        $this->_mode = 'edit';
        $this->_blockGroup = 'ZoZhang_Donation';
        $this->_controller = 'adminhtml_product';
    }

    protected function _addButtons()
    {
        if ($this->_authorization->isAllowed(Acl::PRODUCT_SAVE)) {
            $this->buttonList->update('save', 'label', __('Save'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => [
                                'event' => 'saveAndContinueEdit',
                                'target' => '#edit_form'
                            ],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_authorization->isAllowed(Acl::PRODUCT_DELETE)) {
            $this->buttonList->update('delete', 'label', __('Delete'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    /**
     * @return Phrase
     */
    public function getHeaderText()
    {
        /** @var Object $model */
        $model = $this->_coreRegistry->registry('current_donation_product');
        if ($model->getId()) {
            return __("Edit Product '%1'", $this->escapeHtml($model->getTitle()));
        } else {
            return __('New Product');
        }
    }

}
