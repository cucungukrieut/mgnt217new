<?php

namespace MuliaLestari\ProductsGrid\Controller\Adminhtml\ProdukMaster;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Delete extends \Magento\Backend\App\Action
{

    /**
     * {@inheritdoc}
     */
    /*protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webspeaks_Contact::atachment_delete');
    }*/

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('produk_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create('Webspeaks\ProductsGrid\Model\MasterProduct');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Data master produk telah di hapus.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['produk_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('Tidak dapat menemukan data master produk untuk di hapus.'));
        return $resultRedirect->setPath('*/*/');
    }
}
