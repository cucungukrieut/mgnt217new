<?php
namespace Magento\CatalogML\Model\ResourceModel\Contact;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'produk_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magento\CatalogML\Model\Contact', 'Magento\CatalogML\Model\ResourceModel\Contact');
    }
}
