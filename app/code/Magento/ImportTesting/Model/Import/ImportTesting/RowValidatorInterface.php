<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportTesting\Model\Import\ImportTesting;

interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
       const ERROR_INVALID_SKU= 'InvalidValueSKU';
       const ERROR_SKU_IS_EMPTY = 'EmptySKU';
    /**
     * Initialize validator
     *
     * @return $this
     */
    public function init($context);
}
