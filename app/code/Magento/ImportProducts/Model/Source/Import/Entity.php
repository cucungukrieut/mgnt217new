<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportProducts\Model\Source\Import;

/**
 * Source import entity model
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Entity implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\ImportProducts\Model\Import\ConfigInterface
     */
    protected $_importConfig;

    /**
     * @param \Magento\ImportProducts\Model\Import\ConfigInterface $importConfig
     */
    public function __construct(\Magento\ImportProducts\Model\Import\ConfigInterface $importConfig)
    {
        $this->_importConfig = $importConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $options = [];
        //$options[] = ['label' => __('-- Please Select --'), 'value' => '']; //disable by ahmd
        foreach ($this->_importConfig->getEntities() as $entityName => $entityConfig) {
            if ($entityName == 'catalog_product') { // add by ahmd
                $options[] = ['label' => __($entityConfig['label']), 'value' => $entityName];
            }
        }

       // $optionentity = $options;
        return $options;
    }
}
