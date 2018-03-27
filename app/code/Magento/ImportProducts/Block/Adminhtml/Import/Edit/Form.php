<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportProducts\Block\Adminhtml\Import\Edit;

use Magento\ImportProducts\Model\Import;
use Magento\ImportProducts\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;

/**
 * Import edit form block
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Basic import model
     *
     * @var \Magento\ImportProducts\Model\Import
     */
    protected $_importModel;

    /**
     * @var \Magento\ImportProducts\Model\Source\Import\EntityFactory
     */
    protected $_entityFactory;

    /**
     * @var \Magento\ImportProducts\Model\Source\Import\Behavior\Factory
     */
    protected $_behaviorFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\ImportProducts\Model\Import $importModel
     * @param \Magento\ImportProducts\Model\Source\Import\EntityFactory $entityFactory
     * @param \Magento\ImportProducts\Model\Source\Import\Behavior\Factory $behaviorFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\ImportProducts\Model\Import $importModel,
        \Magento\ImportProducts\Model\Source\Import\EntityFactory $entityFactory,
        \Magento\ImportProducts\Model\Source\Import\Behavior\Factory $behaviorFactory,
        array $data = []
    ) {
        $this->_entityFactory = $entityFactory;
        $this->_behaviorFactory = $behaviorFactory;
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_importModel = $importModel;
    }

    /**
     * Add fieldsets
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('adminhtml/*/validate'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                ],
            ]
        );

        // base fieldset header
        $fieldsets['base'] = $form->addFieldset('base_fieldset', ['legend' => __('Import Settings')]);
        $fieldsets['base']->addField(
            'entity',
            'select',
            [
                'name' => 'entity',
                'title' => __('Entity Type'),
                'label' => __('Entity Type'),
                'required' => true,
                'onchange' => 'varienImport.handleEntityTypeSelector();',
                'values' => $this->_entityFactory->create()->toOptionArray(),
                'after_element_html' => $this->getDownloadSampleFileHtml(),
            ]
        );

        // add behaviour fieldsets
        $uniqueBehaviors = $this->_importModel->getUniqueEntityBehaviors();
        foreach ($uniqueBehaviors as $behaviorCode => $behaviorClass) {
            $fieldsets[$behaviorCode] = $form->addFieldset(
                $behaviorCode . '_fieldset',
                ['legend' => __('Import Behavior'), 'class' => 'no-display']
            );
            /** @var $behaviorSource \Magento\ImportProducts\Model\Source\Import\AbstractBehavior */
            $fieldsets[$behaviorCode]->addField(
                $behaviorCode,
                'select',
                [
                    'name' => 'behavior',
                    'title' => __('Import Behavior'),
                    'label' => __('Import Behavior'),
                    'required' => true,
                    'disabled' => true,
                    'values' => $this->_behaviorFactory->create($behaviorClass)->toOptionArray(),
                    'class' => $behaviorCode,
                    'onchange' => 'varienImport.handleImportBehaviorSelector();',
                    'note' => ' ',
                ]
            );
            $fieldsets[$behaviorCode]->addField(
                $behaviorCode . \Magento\ImportProducts\Model\Import::FIELD_NAME_VALIDATION_STRATEGY,
                'select',
                [
                    'name' => \Magento\ImportProducts\Model\Import::FIELD_NAME_VALIDATION_STRATEGY,
                    'title' => __(' '),
                    'label' => __(' '),
                    'required' => true,
                    'class' => $behaviorCode,
                    'disabled' => true,
                    'values' => [
                        ProcessingErrorAggregatorInterface::VALIDATION_STRATEGY_STOP_ON_ERROR => 'Stop on Error',
                        ProcessingErrorAggregatorInterface::VALIDATION_STRATEGY_SKIP_ERRORS => 'Skip error entries'
                    ],
                    'after_element_html' => $this->getDownloadSampleFileHtml(),
                ]
            );
            $fieldsets[$behaviorCode]->addField(
                $behaviorCode . '_' . \Magento\ImportProducts\Model\Import::FIELD_NAME_ALLOWED_ERROR_COUNT,
                'text',
                [
                    'name' => \Magento\ImportProducts\Model\Import::FIELD_NAME_ALLOWED_ERROR_COUNT,
                    'label' => __('Allowed Errors Count'),
                    'title' => __('Allowed Errors Count'),
                    'required' => true,
                    'disabled' => true,
                    'value' => 5,
                    'class' => $behaviorCode . ' validate-number validate-greater-than-zero input-text',
                    'note' => __(
                        'Please specify number of errors to halt import process'
                    ),
                ]
            );
            $fieldsets[$behaviorCode]->addField(
                $behaviorCode . '_' . \Magento\ImportProducts\Model\Import::FIELD_FIELD_SEPARATOR,
                'text',
                [
                    'name' => \Magento\ImportProducts\Model\Import::FIELD_FIELD_SEPARATOR,
                    'label' => __('Field separator'),
                    'title' => __('Field separator'),
                    'required' => true,
                    'disabled' => true,
                    'class' => $behaviorCode,
                    'value' => ';',
                ]
            );
            $fieldsets[$behaviorCode]->addField(
                $behaviorCode . \Magento\ImportProducts\Model\Import::FIELD_FIELD_MULTIPLE_VALUE_SEPARATOR,
                'text',
                [
                    'name' => \Magento\ImportProducts\Model\Import::FIELD_FIELD_MULTIPLE_VALUE_SEPARATOR,
                    'label' => __('Multiple value separator'),
                    'title' => __('Multiple value separator'),
                    'required' => true,
                    'disabled' => true,
                    'class' => $behaviorCode,
                    'value' => Import::DEFAULT_GLOBAL_MULTI_VALUE_SEPARATOR,
                ]
            );
            $fieldsets[$behaviorCode]->addField(
                $behaviorCode . \Magento\ImportProducts\Model\Import::FIELDS_ENCLOSURE,
                'checkbox',
                [
                    'name' => \Magento\ImportProducts\Model\Import::FIELDS_ENCLOSURE,
                    'label' => __('Fields enclosure'),
                    'title' => __('Fields enclosure'),
                    'value' => 1,
                ]
            );
        }

        // fieldset for file uploading
        $fieldsets['upload'] = $form->addFieldset(
            'upload_file_fieldset',
            ['legend' => __('File to Import'), 'class' => 'no-display']
        );
        //button file chooser
        $fieldsets['upload']->addField(
            \Magento\ImportProducts\Model\Import::FIELD_NAME_SOURCE_FILE,
            'file',
            [
                'name' => \Magento\ImportProducts\Model\Import::FIELD_NAME_SOURCE_FILE,
                'label' => __('Select File to Import'),
                'title' => __('Select File to Import'),
                'required' => true,
                'class' => 'input-file'
            ]
        );

        //field untuk path upload image
        $fieldsets['upload']->addField(
            \Magento\ImportProducts\Model\Import::FIELD_NAME_IMG_FILE_DIR,
            'text',
            [
                'name' => \Magento\ImportProducts\Model\Import::FIELD_NAME_IMG_FILE_DIR,
                'label' => __('Images File Directory'),
                'title' => __('Images File Directory'),
                'required' => false,
                'class' => 'input-text',
                'note' => __(
                    'For Type "Local Server" use relative path to Magento installation,
                                e.g. var/export, var/import, var/export/some/dir'
                ),
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Get download sample file html
     * Link untuk download file sample import
     *
     * @return string
     */
    protected function getDownloadSampleFileHtml()
    {
        $html = '<span id="sample-file-span" class="no-display"><a id="sample-file-link" href="#">'
            . __('Download Sample')
            . '</a></span>';
        return $html;
    }
}
