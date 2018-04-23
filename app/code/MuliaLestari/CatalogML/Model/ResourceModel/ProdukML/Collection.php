<?php
namespace MuliaLestari\CatalogML\Model\ResourceModel\ProdukML;


/**
 * Class Collection
 * @package MuliaLestari\CatalogML\Model\ResourceModel\ProdukML
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'grouping_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MuliaLestari\CatalogML\Model\ProdukML', 'MuliaLestari\CatalogML\Model\ResourceModel\ProdukML');
    }
}
