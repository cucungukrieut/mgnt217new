<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportProducts\Controller\Adminhtml\Import;

use Magento\ImportProducts\Controller\Adminhtml\Import as ImportController;
use Magento\Framework\Controller\ResultFactory;

class Index extends ImportController
{
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $this->messageManager->addNotice(
            $this->_objectManager->get('Magento\ImportProducts\Helper\Data')->getMaxUploadSizeMessage()
        );
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Magento_ImportProducts::system_convert_import');
        $resultPage->getConfig()->getTitle()->prepend(__('Import Products'));
        $resultPage->getConfig()->getTitle()->prepend(__('Import'));
        $resultPage->addBreadcrumb(__('Import'), __('Import'));
        return $resultPage;
    }
}
