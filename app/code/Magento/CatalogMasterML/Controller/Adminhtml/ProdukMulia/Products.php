<?php

namespace Magento\CatalogMasterML\Controller\Adminhtml\ProdukMulia;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;


/**
 * Class Products
 * @package Magento\CatalogMasterML\Controller\Adminhtml\ProdukMulia
 */
class Products extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $_resultLayoutFactory;

    /**
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param Action\Context $context
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->_resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return true;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultLayout = $this->_resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('produk.edit.tab.products')
                     ->setInProducts($this->getRequest()->getPost('produk_products', null));

        return $resultLayout;
    }
}
