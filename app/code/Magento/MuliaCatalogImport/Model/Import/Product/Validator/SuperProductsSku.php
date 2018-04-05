<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MuliaCatalogImport\Model\Import\Product\Validator;

use Magento\MuliaCatalogImport\Model\Import\Product\Validator\AbstractImportValidator;
use Magento\MuliaCatalogImport\Model\Import\Product\RowValidatorInterface;

class SuperProductsSku extends AbstractImportValidator implements RowValidatorInterface
{
    /**
     * @var \Magento\MuliaCatalogImport\Model\Import\Product\SkuProcessor
     */
    protected $skuProcessor;

    /**
     * @param \Magento\MuliaCatalogImport\Model\Import\Product\SkuProcessor $skuProcessor
     */
    public function __construct(
        \Magento\MuliaCatalogImport\Model\Import\Product\SkuProcessor $skuProcessor
    ) {
        $this->skuProcessor = $skuProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function init($context)
    {
        return parent::init($context);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->_clearMessages();
        $oldSku = $this->skuProcessor->getOldSkus();
        if (!empty($value['_super_products_sku']) && (!isset(
                $oldSku[$value['_super_products_sku']]
            ) && $this->skuProcessor->getNewSku($value['_super_products_sku']) === null
            )
        ) {
            $this->_addMessages([self::ERROR_SUPER_PRODUCTS_SKU_NOT_FOUND]);
            return false;
        }
        return true;

    }
}
