<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportProducts\Model\ResourceModel\History;

use \Magento\ImportProducts\Model\History;

/**
 * Import history collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Link table name
     *
     * @var string
     */
    protected $_linkTable;

    /**
     * Define resource model and assign link table name
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magento\ImportProducts\Model\History', 'Magento\ImportProducts\Model\ResourceModel\History');
        $this->_linkTable = $this->getTable('admin_user');
    }

    /**
     * Init select
     *
     * @return \Magento\ImportProducts\Model\ResourceModel\History\Collection
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->joinLeft(
            ['link_table' => $this->_linkTable],
            'link_table.user_id = main_table.user_id',
            ['username']
        )->where(
            'execution_time != ? OR (error_file != "" AND execution_time = ?)',
            History::IMPORT_VALIDATION,
            History::IMPORT_VALIDATION
        );

        return $this;
    }
}
