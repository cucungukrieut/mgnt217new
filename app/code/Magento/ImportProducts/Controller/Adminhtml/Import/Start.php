<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportProducts\Controller\Adminhtml\Import;

use Magento\ImportProducts\Controller\Adminhtml\ImportResult as ImportResultController;
use Magento\Framework\Controller\ResultFactory;

class Start extends ImportResultController
{
    /**
     * @var \Magento\ImportProducts\Model\Import
     */
    protected $importModel;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\ImportProducts\Model\Report\ReportProcessorInterface $reportProcessor
     * @param \Magento\ImportProducts\Model\History $historyModel
     * @param \Magento\ImportProducts\Helper\Report $reportHelper
     * @param \Magento\ImportProducts\Model\Import $importModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\ImportProducts\Model\Report\ReportProcessorInterface $reportProcessor,
        \Magento\ImportProducts\Model\History $historyModel,
        \Magento\ImportProducts\Helper\Report $reportHelper,
        \Magento\ImportProducts\Model\Import $importModel
    ) {
        parent::__construct($context, $reportProcessor, $historyModel, $reportHelper);
        $this->importModel = $importModel;
    }

    /**
     * Start import process action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            /** @var \Magento\Framework\View\Result\Layout $resultLayout */
            $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
            /** @var $resultBlock \Magento\ImportProducts\Block\Adminhtml\Import\Frame\Result */
            $resultBlock = $resultLayout->getLayout()->getBlock('import.frame.result');
            $resultBlock
                ->addAction('show', 'import_validation_container')
                ->addAction('innerHTML', 'import_validation_container_header', __('Status'))
                ->addAction('hide', ['edit_form', 'upload_button', 'messages']);

            $this->importModel->setData($data);
            $this->importModel->importSource();
            $errorAggregator = $this->importModel->getErrorAggregator();
            if ($this->importModel->getErrorAggregator()->hasToBeTerminated()) {
                $resultBlock->addError(__('Maximum error count has been reached or system error is occurred!'));
                $this->addErrorMessages($resultBlock, $errorAggregator);
            } else {
                $this->importModel->invalidateIndex();
                $this->addErrorMessages($resultBlock, $errorAggregator);
                $resultBlock->addSuccess(__('Import berhasil..'));
            }

            return $resultLayout;
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('adminhtml/*/index');
        return $resultRedirect;
    }
}
