<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportProducts\Model\Source\Import\Behavior;

/**
 * Import behavior source model used for defining the behaviour during the import.
 */
class Basic extends \Magento\ImportProducts\Model\Source\Import\AbstractBehavior
{
    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            \Magento\ImportProducts\Model\Import::BEHAVIOR_APPEND => __('Tambah/Update'),
            \Magento\ImportProducts\Model\Import::BEHAVIOR_REPLACE => __('Ganti'),
            \Magento\ImportProducts\Model\Import::BEHAVIOR_DELETE => __('Hapus')
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return 'basic';
    }

    /**
     * {@inheritdoc}
     */
    public function getNotes($entityCode)
    {
        $messages = ['catalog_product' => [
            \Magento\ImportProducts\Model\Import::BEHAVIOR_REPLACE => __("Note: Product IDs will be regenerated.")
        ]];
        return isset($messages[$entityCode]) ? $messages[$entityCode] : [];
    }
}
