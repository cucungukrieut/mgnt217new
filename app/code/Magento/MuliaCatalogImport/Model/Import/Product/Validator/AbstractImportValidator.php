<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MuliaCatalogImport\Model\Import\Product\Validator;

use Magento\Framework\Validator\AbstractValidator;
use Magento\MuliaCatalogImport\Model\Import\Product\RowValidatorInterface;

abstract class AbstractImportValidator extends AbstractValidator implements RowValidatorInterface
{
    /**
     * @var \Magento\MuliaCatalogImport\Model\Import\Product
     */
    protected $context;

    /**
     * @param \Magento\MuliaCatalogImport\Model\Import\Product $context
     * @return $this
     */
    public function init($context)
    {
        $this->context = $context;
        return $this;
    }
}
