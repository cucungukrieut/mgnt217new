<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Test class for \Magento\ImportProducts\Model\Export\AbstractEntity
 */
namespace Magento\ImportProducts\Test\Unit\Model\Export;

class EntityAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test for setter and getter of file name property
     *
     * @covers \Magento\ImportProducts\Model\Export\AbstractEntity::getFileName
     * @covers \Magento\ImportProducts\Model\Export\AbstractEntity::setFileName
     */
    public function testGetFileNameAndSetFileName()
    {
        /** @var $model \Magento\ImportProducts\Model\Export\AbstractEntity */
        $model = $this->getMockForAbstractClass(
            'Magento\ImportProducts\Model\Export\AbstractEntity',
            [],
            'Stub_UnitTest_Magento_ImportProducts_Model_Export_Entity_TestSetAndGet',
            false
        );

        $testFileName = 'test_file_name';

        $fileName = $model->getFileName();
        $this->assertNull($fileName);

        $model->setFileName($testFileName);
        $this->assertEquals($testFileName, $model->getFileName());

        $fileName = $model->getFileName();
        $this->assertEquals($testFileName, $fileName);
    }
}
