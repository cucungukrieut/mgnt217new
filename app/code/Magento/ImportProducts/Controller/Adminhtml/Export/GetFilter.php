<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportProducts\Controller\Adminhtml\Export;

use Magento\ImportProducts\Controller\Adminhtml\Export as ExportController;
use Magento\Framework\Controller\ResultFactory;

class GetFilter extends ExportController
{
    /**
     * Get grid-filter of entity attributes action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        if ($this->getRequest()->isXmlHttpRequest() && $data) {
            try {
                /** @var \Magento\Framework\View\Result\Layout $resultLayout */
                $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
                /** @var $attrFilterBlock \Magento\ImportProducts\Block\Adminhtml\Export\Filter */
                $attrFilterBlock = $resultLayout->getLayout()->getBlock('export.filter');
                /** @var $export \Magento\ImportProducts\Model\Export */
                $export = $this->_objectManager->create('Magento\ImportProducts\Model\Export');
                $export->setData($data);

                $export->filterAttributeCollection(
                    $attrFilterBlock->prepareCollection($export->getEntityAttributeCollection())
                );
                return $resultLayout;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        } else {
            $this->messageManager->addError(__('Please correct the data sent value.'));
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('adminhtml/*/index');
        return $resultRedirect;
    }
}
