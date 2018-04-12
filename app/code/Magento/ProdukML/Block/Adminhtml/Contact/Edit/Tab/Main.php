<?php
namespace Magento\ProdukML\Block\Adminhtml\Contact\Edit\Tab;

use \Magento\Backend\Block\Widget\Form\Generic;
use \Magento\Backend\Block\Widget\Tab\TabInterface;
use \Magento\Backend\Block\Template\Context;
use \Magento\Framework\Registry;
use \Magento\Framework\Data\FormFactory;
use \Magento\ProdukML\Helper\Data;

class Main extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $store;

    /**
    * @var \Magento\ProdukML\Helper\Data $helper
    */
    protected $helper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(Context $context, Registry $registry,
                                FormFactory $formFactory, Data $helper, array $data = [])
    {
        $this->helper = $helper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Magento\ProdukML\Model\Contact */
        $model = $this->_coreRegistry->registry('ws_contact');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('contact_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Contact Information')]);

        if ($model->getId()) {
            $fieldset->addField('contact_id', 'hidden', ['name' => 'contact_id']);
        }

        $fieldset->addField(
            'contact_name',
            'text',
            [
                'name' => 'contact_name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'age',
            'text',
            [
                'name' => 'age',
                'label' => __('Age'),
                'title' => __('Age'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'phone',
            'text',
            [
                'name' => 'phone',
                'label' => __('Phone'),
                'title' => __('Phone'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'address',
            'textarea',
            [
                'name' => 'address',
                'label' => __('Address'),
                'title' => __('Address'),
                'required' => true,
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Main');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Main');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
