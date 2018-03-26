<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportProducts\Model\Source\Import\Behavior;

/**
 * Import behavior source model
 */
class Custom extends \Magento\ImportProducts\Model\Source\Import\AbstractBehavior
{
    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            \Magento\ImportProducts\Model\Import::BEHAVIOR_ADD_UPDATE => __('Add/Update Complex Data'),
            \Magento\ImportProducts\Model\Import::BEHAVIOR_DELETE => __('Delete Entities'),
            \Magento\ImportProducts\Model\Import::BEHAVIOR_CUSTOM => __('Custom Action')
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return 'custom';
    }
}
