<?php

namespace Magento\ProductsGrid\Block\Adminhtml;

use \Magento\Backend\Block\Widget\Grid\Container;

/**
 * Adminhtml contact content block
 */
class Contact extends Container
{
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
