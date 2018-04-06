<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportTesting\Model\Import\ImportTesting;

use \Magento\Framework\Validator\ValidatorInterface;

interface RowValidatorInterface extends ValidatorInterface {

    const ERROR_INVALID_SKU= 'InvalidValueSKU';
    const ERROR_SKU_IS_EMPTY = 'EmptySKU';

    /**
     * Initialize validator
     *
     * @param $context
     * @return $this
     */
    public function init($context);
}
