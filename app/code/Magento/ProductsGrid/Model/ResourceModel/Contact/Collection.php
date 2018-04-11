<?php
namespace Magento\ProductsGrid\Model\ResourceModel\Contact;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'contact_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magento\ProductsGrid\Model\Contact', 'Magento\ProductsGrid\Model\ResourceModel\Contact');
    }
}
