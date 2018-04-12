<?php
namespace Magento\ProdukML\Model;

use Magento\Framework\DataObject\IdentityInterface;
use \Magento\Framework\Model\AbstractModel;

class Contact extends AbstractModel implements IdentityInterface
{

    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'ws_products_grid';

    /**
     * @var string
     */
    protected $_cacheTag = 'ws_products_grid';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'ws_products_grid';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magento\ProdukML\Model\ResourceModel\Contact');
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

    public function getProducts(\Magento\ProdukML\Model\Contact $object)
    {
        $tbl = $this->getResource()->getTable(\Magento\ProdukML\Model\ResourceModel\Contact::TBL_ATT_PRODUCT);
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['product_id']
        )
        ->where(
            'contact_id = ?',
            (int)$object->getId()
        );
        return $this->getResource()->getConnection()->fetchCol($select);
    }
}
