<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportProducts\Model\Source\Export;

/**
 * Source export entity model
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Entity implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\ImportProducts\Model\Export\ConfigInterface
     */
    protected $_exportConfig;

    /**
     * @param \Magento\ImportProducts\Model\Export\ConfigInterface $exportConfig
     */
    public function __construct(\Magento\ImportProducts\Model\Export\ConfigInterface $exportConfig)
    {
        $this->_exportConfig = $exportConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = ['label' => __('-- Please Select --'), 'value' => ''];
        foreach ($this->_exportConfig->getEntities() as $entityName => $entityConfig) {
            $options[] = ['value' => $entityName, 'label' => __($entityConfig['label'])];
        }
        return $options;
    }
}
