<?php

namespace MuliaLestari\CatalogML\Block\Adminhtml;

/**
 * Class ProdukML || Adminhtml ProdukML content block
 * @package MuliaLestari\CatalogML\Block\Adminhtml
 */
class ProdukML extends \Magento\Backend\Block\Widget\Grid\Container
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
