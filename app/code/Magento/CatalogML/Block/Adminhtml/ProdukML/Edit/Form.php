<?php

namespace Magento\CatalogML\Block\Adminhtml\ProdukML\Edit;

/**
 * Class Form
 * Adminhtml attachment edit form block
 *
 * @package Magento\CatalogML\Block\Adminhtml\ProdukML\Edit
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' =>
                [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post'
                ]
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
