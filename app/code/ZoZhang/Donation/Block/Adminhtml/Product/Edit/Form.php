<?php

/**
 * Donation Product
 *
 * @author Zhao ZHANG
 * @email <zo.zhang@gmail.com>
 * @site https://zozhang.github.io
 */

namespace ZoZhang\Donation\Block\Adminhtml\Product\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;
use Magento\Framework\Data\Form as DataForm;
use ZoZhang\Donation\Api\Data\ProductInterface;

/**
 * Class Form
 * @package ZoZhang\Donation\Block\Adminhtml\Product\Edit
 */
class Form extends Generic
{
    /**
     * @var Config
     */
    protected $_wysiwygConfig;

    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    /**
     * @var Store
     */
    protected $_store;

    /**
     * @param Context $store
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Store  $store,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_store = $store;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('donation_product_form');
        $this->setTitle(__('Donation Product Information'));
    }

    /**
     * @return Form
     */
    protected function _prepareForm()
    {
        /** @var ProductInterface|AbstractModel $model */
        $model = $this->_coreRegistry->registry('current_donation_product');

        /** @var DataForm $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ]
            ]
        );

        $form->setHtmlIdPrefix('donation_product_');
        $form->setUseContainer(true);

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }

        $fieldset->addField(
            ProductInterface::STORE_IDS,
            'multiselect',
            [
                'name' => ProductInterface::STORE_IDS,
                'label' => __('Store Views'),
                'title' => __('Store Views'),
                'note' => __('Select Store Views'),
                'required' => true,
                'values' => $this->_store->getStoreValuesForForm(false, true),
            ]
        );

        $fieldset->addField(
            ProductInterface::TITLE,
            'text',
            [
                'name' => ProductInterface::TITLE,
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true
            ]
        );

        $fieldset->addField(
            ProductInterface::SKU,
            'text',
            [
                'name' => ProductInterface::SKU,
                'label' => __('SKU'),
                'title' => __('SKU'),
                'required' => true,
                'note' => __('Important information: Please make sure that the SKU has a product list before saving.')
            ]
        );

        $fieldset->addField(
            ProductInterface::AMOUNT,
            'text',
            [
                'name' => ProductInterface::AMOUNT,
                'label' => __('Amount'),
                'title' => __('Amount'),
                'class' => 'required-entry validate-zero-or-greater validate-number',
                'required' => true
            ]
        );

        $fieldset->addField(
            ProductInterface::DESCRIPTION,
            'editor',
            [
                'name' => ProductInterface::DESCRIPTION,
                'label' => __('Content'),
                'title' => __('Content'),
                'required' => true,
                'rows' => '5',
                'cols' => '30',
                'wysiwyg' => true,
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);
        return $this;
    }
}
