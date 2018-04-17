<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportWarnaML\Model\Import\ImportWarnaML;

use \Magento\Framework\Validator\ValidatorInterface;

interface RowValidatorInterface extends ValidatorInterface {

    const ERROR_INVALID_KODE= 'InvalidValueKODE';
    const ERROR_KODE_IS_EMPTY = 'EmptyKODE';

    /**
     * Initialize validator
     *
     * @param $context
     * @return $this
     */
    public function init($context);
}
