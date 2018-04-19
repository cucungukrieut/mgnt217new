<?php

namespace Magento\CatalogMasterML\Model;

use Magento\Framework\DataObject\IdentityInterface;

class ProdukMasterML extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{

    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'ml_masterproduk_grid';

    /**
     * @var string
     */
    protected $_cacheTag = 'ml_masterproduk_grid';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'ml_masterproduk_grid';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magento\CatalogMasterML\Model\ResourceModel\ProdukMasterML');
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }


    /**
     * Get products from DB table
     * @param ProdukMasterML $object
     * @return array
     *
    public function getProducts(\Magento\CatalogMasterML\Model\ProdukMasterML $object)
    {
        $tbl = $this->getResource()->getTable(\Magento\CatalogMasterML\Model\ResourceModel\ProdukMasterML::TBL_ATT_PRODUCT);
        $select = $this->getResource()->getConnection()
            ->select()->from($tbl, ['product_id'])
            ->where('produk_id = ?', (int)$object->getId()
        );

        //$arrayproduk = $this->getResource()->getConnection()->fetchCol($select);
        return $this->getResource()->getConnection()->fetchCol($select);
    }*/
}
