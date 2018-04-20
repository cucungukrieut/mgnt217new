<?php
namespace MuliaLestari\ProductsGrid\Model\ResourceModel\MasterProduct;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     * getting product id for grid product
     */
    protected $_idFieldName = 'produk_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MuliaLestari\ProductsGrid\Model\MasterProduct', 'MuliaLestari\ProductsGrid\Model\ResourceModel\MasterProduct');
    }
}
