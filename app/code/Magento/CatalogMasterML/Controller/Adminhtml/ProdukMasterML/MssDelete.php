<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Catalog\Controller\Adminhtml\Product;

use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Controller\Adminhtml\Product\Builder;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\CatalogMasterML\Model\ResourceModel\ProdukMasterML\CollectionFactory;

class MssDelete extends \Magento\Backend\App\Action
{
    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     //* @param Builder $productBuilder
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        //Builder $productBuilder,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context); //, $productBuilder
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        //$collectionSize = $collection->getSize();
        $productDeleted = 0;
        foreach ($collection as $item) {
            $item->delete();
            $productDeleted++;
        }


        //$collection = $this->filter->getCollection($this->collectionFactory->create());
        //$productDeleted = 0;
        //foreach ($collection->getItems() as $product) {
        //    $product->delete();
        //    $productDeleted++;
        //}
        $this->messageManager->addSuccessMessage(__('Total %1 record telah di hapus.', $productDeleted)
        );
        //$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        //return $resultRedirect->setPath('*/*/');
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('catalogmasterml/*/index');
    }

    /**
     * Class MassDelete
     *
        class MassDelete extends \Magento\Backend\App\Action
        {
            protected $_coreRegistry = null;

            /**
             * @var \Magento\Framework\View\Result\PageFactory
             *
            protected $resultPageFactory;

            protected $moduleFactory;

            /**
             * @param Action\Context $context
             * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
             * @param \Magento\Framework\Registry $registry
             *
            public function __construct(
                Action\Context $context,
                \Magento\Framework\View\Result\PageFactory $resultPageFactory,
                \Magento\Framework\Registry $registry,
                Filter $filter,
                CollectionFactory $module
            ) {
                $this->resultPageFactory = $resultPageFactory;
                $this->_coreRegistry = $registry;
                $this->moduleFactory = $module;
                $this->filter = $filter;
                parent::__construct($context);
            }


            /**
             * Execute action
             *
             * @return \Magento\Backend\Model\View\Result\Redirect
             * @throws \Magento\Framework\Exception\LocalizedException|\Exception
             *
            public function execute()
            {
                $deleteIds = $this->getRequest()->getParams('selected');

                $collection = $this->moduleFactory->create();

                $collection->addFieldToFilter('id', array('in' => $deleteIds));

                $count = 0;
                foreach ($collection as $child) {
                    $child->delete();
                    $count++;
                }

                $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $count));

                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect *
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('//');
            }
        }

     */
}
