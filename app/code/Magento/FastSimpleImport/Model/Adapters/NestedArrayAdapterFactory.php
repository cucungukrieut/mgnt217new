<?php
/**
 * Copyright © 2016 Magento e.V. - All rights reserved.
 * See LICENSE.md bundled with this module for license details.
 */
namespace Magento\FastSimpleImport\Model\Adapters;
class NestedArrayAdapterFactory implements ImportAdapterFactoryInterface
{
    protected $_objectManager = null;

    protected $_instanceName = null;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $instanceName = 'Magento\FastSimpleImport\Model\Adapters\NestedArrayAdapter'
    )
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * @param array $data
     * @return \Magento\FastSimpleImport\Model\Adapters\NestedArrayAdapter
     */
    public function create(array $data = [])
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}