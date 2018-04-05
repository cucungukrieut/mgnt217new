<?php
/**
 * Copyright © 2016 Magento e.V. - All rights reserved.
 * See LICENSE.md bundled with this module for license details.
 */

namespace Magento\FastSimpleImport\Model\Adapters;
interface ImportAdapterFactoryInterface{
    /**
     * @return \Magento\ImportExport\Model\Import\AbstractSource
     */
    public function create(array $data = []);
}