<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Test class for \Magento\ImportProducts\Model\Import\AbstractEntity
 */
namespace Magento\ImportProducts\Model\Import;

use Magento\Framework\App\Filesystem\DirectoryList;

class EntityAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test for method _saveValidatedBunches()
     */
    public function testSaveValidatedBunches()
    {
        $filesystem = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Framework\Filesystem');
        $directory = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
        $source = new \Magento\ImportProducts\Model\Import\Source\Csv(
            __DIR__ . '/Entity/_files/customers_for_validation_test.csv',
            $directory
        );
        $source->rewind();
        $expected = $source->current();

        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var $model \Magento\ImportProducts\Model\Import\AbstractEntity|\PHPUnit_Framework_MockObject_MockObject */
        $model = $this->getMockForAbstractClass(
            'Magento\ImportProducts\Model\Import\AbstractEntity',
            [
                $objectManager->get('Magento\Framework\Stdlib\StringUtils'),
                $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface'),
                $objectManager->get('Magento\ImportProducts\Model\ImportFactory'),
                $objectManager->get('Magento\ImportProducts\Model\ResourceModel\Helper'),
                $objectManager->get('Magento\Framework\App\ResourceConnection'),
                $objectManager->get(
                    'Magento\ImportProducts\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface'
                )
            ],
            '',
            true,
            false,
            true,
            ['getMasterAttributeCode', 'validateRow', 'getEntityTypeCode']
        );
        $model->expects($this->any())->method('getMasterAttributeCode')->will($this->returnValue("email"));
        $model->expects($this->any())->method('validateRow')->will($this->returnValue(true));
        $model->expects($this->any())->method('getEntityTypeCode')->will($this->returnValue('customer'));

        $model->setSource($source);

        $method = new \ReflectionMethod($model, '_saveValidatedBunches');
        $method->setAccessible(true);
        $method->invoke($model);

        /** @var $dataSourceModel \Magento\ImportProducts\Model\ResourceModel\Import\Data */
        $dataSourceModel = $objectManager->get('Magento\ImportProducts\Model\ResourceModel\Import\Data');
        $this->assertCount(1, $dataSourceModel->getIterator());

        $bunch = $dataSourceModel->getNextBunch();
        $this->assertEquals($expected, $bunch[0]);

        //Delete created bunch from DB
        $dataSourceModel->cleanBunches();
    }
}
