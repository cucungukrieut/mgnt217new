<?php

namespace Magento\CatalogML\Controller\Adminhtml\Contacts;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Delete extends \Magento\Backend\App\Action
{

    /**
     * {@inheritdoc}
     */
    /*protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_CatalogML::atachment_delete');
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
                $model = $this->_objectManager->create('Magento\CatalogML\Model\Contact');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Produk telah di hapus.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['produk_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('Tidak bisa menemukan produk untuk dihapus.'));
        return $resultRedirect->setPath('*/*/');
    }
}
