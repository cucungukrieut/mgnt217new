<?php
namespace Magento\CatalogML\Block\Adminhtml\ProdukML\Edit\Tab;


/**
 * Class Main
 * @package Magento\CatalogML\Block\Adminhtml\ProdukML\Edit\Tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $store;

    /**
    * @var \Magento\CatalogML\Helper\Data $helper
    */
    protected $helper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\CatalogML\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     ***************************************************
     * FORM UNTUK MENAMBAH, EDIT DAN DELETE DATA PRODUK
     ***************************************************
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Magento\CatalogML\Model\ProdukML */
        $model = $this->_coreRegistry->registry('ml_produk');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('produk_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Informasi Produk')]);


        // FIELD FORM ISIAN DATA PRODUK
        if ($model->getId()) {
            $fieldset->addField(
                'grouping_id',
                'hidden',
                [
                    'nama' => 'grouping_id'
                ]
            );
        }

        $fieldset->addField(
            'created',
            'date',
            [
                'name' => 'created',
                'label' => __('Created'),
                'title' => __('Created'),
                'required' => true,
                'date_format' => 'yyyy-MM-dd',
                'time_format' => 'hh:mm:ss'
            ]
        );

        $fieldset->addField(
            'updated',
            'date',
            [
                'name' => 'updated',
                'label' => __('Updated'),
                'title' => __('Updated'),
                'required' => true,
                'date_format' => 'yyyy-MM-dd',
                'time_format' => 'hh:mm:ss'
            ]
        );

        $fieldset->addField(
            'custom_kode',
            'text',
            [
                'name' => 'custom_kode',
                'label' => __('Custom Kode'),
                'title' => __('Custom Kode'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'nama',
            'text',
            [
                'name' => 'nama',
                'label' => __('Nama'),
                'title' => __('Nama'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'active',
            'checkbox',
            [
                'name' => 'isactive',
                'label' => __('Active'),
                'title' => __('Active'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'deskripsi',
            'textarea',
            [
                'name' => 'deskripsi',
                'label' => __('Deskripsi'),
                'title' => __('Deskripsi'),
                'required' => true,
            ]
        );


        //$dataproduk = $model->getData();

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
        return __('Produk ML');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Produk ML');
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
