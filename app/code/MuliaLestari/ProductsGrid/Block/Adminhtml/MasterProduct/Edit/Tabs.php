<?php

namespace MuliaLestari\ProductsGrid\Block\Adminhtml\MasterProduct\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('contact_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('MasterProduct Information'));
    }
}
