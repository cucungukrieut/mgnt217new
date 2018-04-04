<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportProducts\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\ImportProducts\Model\Import\Entity\AbstractEntity;
use Magento\ImportProducts\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;

/**
 * Import controller
 */
abstract class Import extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_ImportProducts::import';
}
