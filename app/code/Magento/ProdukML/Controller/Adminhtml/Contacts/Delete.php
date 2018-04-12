<?php

namespace Magento\ProdukML\Controller\Adminhtml\Contacts;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Delete extends Action
{

    /**
     * {@inheritdoc}
     */
    /*protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Contact::atachment_delete');
    }*/

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('contact_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create('Magento\ProdukML\Model\Contact');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Contact telah di hapus.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['contact_id' => $id]);
            }
        }
        $this->messageManager->addError(__('Tidak dapat mencari kontak untuk di hapus.'));
        return $resultRedirect->setPath('*/*/');
    }
}
