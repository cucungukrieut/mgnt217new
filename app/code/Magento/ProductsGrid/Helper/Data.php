<?php
namespace Magento\ProductsGrid\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Helper\Context;
use \Magento\Backend\Model\UrlInterface;
use \Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(Context $context, UrlInterface $backendUrl,StoreManagerInterface $storeManager) {
        parent::__construct($context);
        $this->_backendUrl = $backendUrl;
        $this->storeManager = $storeManager;
    }

    /**
     * get products tab Url in admin
     * @return string
     */
    public function getProductsGridUrl()
    {
        return $this->_backendUrl->getUrl('wsproductsgrid/contacts/products', ['_current' => true]);
    }
}
