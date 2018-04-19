<?php
namespace Magento\CatalogMasterML\Block\Adminhtml\ProdukMasterML\Edit\Tab;


/**
 * Class Main
 * @package Magento\CatalogMasterML\Block\Adminhtml\ProdukMasterML\Edit\Tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $store;

    /**
    * @var \Magento\CatalogMasterML\Helper\Data $helper
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
        \Magento\CatalogMasterML\Helper\Data $helper,
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
        /* @var $model \Magento\CatalogMasterML\Model\ProdukMasterML */
        $model = $this->_coreRegistry->registry('ml_masterproduk');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('produk_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Informasi Produk')]);


        // FIELD FORM ISIAN DATA PRODUK
        if ($model->getId()) {
            $fieldset->addField(
                'produk_id',
                'hidden',
                [
                    'nama' => 'produk_id'
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
            'kode',
            'text',
            [
                'name' => 'kode',
                'label' => __('Kode'),
                'title' => __('Kode'),
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
            'qty_bruto',
            'text',
            [
                'name' => 'qty_bruto',
                'label' => __('Qty Bruto'),
                'title' => __('Qty Bruto'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'qty_netto',
            'text',
            [
                'name' => 'qty_netto',
                'label' => __('Qty Netto'),
                'title' => __('Qty Netto'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'kategori',
            'text',
            [
                'name' => 'kategori',
                'label' => __('Kategori'),
                'title' => __('Kategori'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'harga',
            'text',
            [
                'name' => 'harga',
                'label' => __('Harga'),
                'title' => __('Harga'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'lebar',
            'text',
            [
                'name' => 'lebar',
                'label' => __('Lebar'),
                'title' => __('Lebar'),
                'required' => false,
            ]
        );

        $fieldset->addField(
            'gramasi',
            'text',
            [
                'name' => 'gramasi',
                'label' => __('Gramasi'),
                'title' => __('Gramasi'),
                'required' => false,
            ]
        );

        $fieldset->addField(
            'lot',
            'text',
            [
                'name' => 'lot',
                'label' => __('Lot'),
                'title' => __('Lot'),
                'required' => false,
            ]
        );


        $fieldset->addField(
            'kategori_warna',
            'text',
            [
                'name' => 'kategori_warna',
                'label' => __('Kategori Warna'),
                'title' => __('Kategori Warna'),
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
        return __('Produk Master ML');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Produk Master ML');
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
