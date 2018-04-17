<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportWarnaML\Model\Import\Validator;

use Magento\Framework\Validator\AbstractValidator;
use Magento\ImportWarnaML\Model\Import\ImportWarnaML\RowValidatorInterface;

abstract class AbstractImportValidator extends AbstractValidator implements RowValidatorInterface {

    /**
     * @var \Magento\CatalogImportExport\Model\Import\Product
     */
    protected $context;

    /**
     * @param \Magento\CatalogImportExport\Model\Import\Product $context
     * @return $this
     */
    public function init($context) {
        $this->context = $context;
        return $this;
    }
}
