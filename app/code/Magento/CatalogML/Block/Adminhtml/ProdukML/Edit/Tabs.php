<?php

namespace Magento\CatalogML\Block\Adminhtml\ProdukML\Edit;

/**
 * Class Tabs || Admin page left menu
 * @package Magento\CatalogML\Block\Adminhtml\ProdukML\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('produk_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Informasi Produk'));
    }
}
