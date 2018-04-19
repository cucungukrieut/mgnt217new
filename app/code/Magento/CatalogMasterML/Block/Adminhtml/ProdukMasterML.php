<?php

namespace Magento\CatalogMasterML\Block\Adminhtml;

/**
 * Class ProdukMasterML || Adminhtml ProdukMasterML content block
 * @package Magento\CatalogMasterML\Block\Adminhtml
 */
class ProdukMasterML extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected $data = [];

    /**
     * Block constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::__construct($context, $data);
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
