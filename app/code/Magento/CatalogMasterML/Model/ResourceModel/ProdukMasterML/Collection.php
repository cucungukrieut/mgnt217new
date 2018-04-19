<?php
namespace Magento\CatalogMasterML\Model\ResourceModel\ProdukMasterML;


/**
 * Class Collection
 * @package Magento\CatalogMasterML\Model\ResourceModel\ProdukMasterML
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string id
     * untuk check id (used on massdelete)
     */
    protected $_idFieldName = 'produk_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magento\CatalogMasterML\Model\ProdukMasterML', 'Magento\CatalogMasterML\Model\ResourceModel\ProdukMasterML');
    }
}
