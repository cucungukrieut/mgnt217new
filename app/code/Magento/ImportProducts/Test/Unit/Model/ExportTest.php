<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Test class for \Magento\ImportProducts\Model\Export
 */
namespace Magento\ImportProducts\Test\Unit\Model;

class ExportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Extension for export file
     *
     * @var string
     */
    protected $_exportFileExtension = 'csv';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_exportConfigMock;

    /**
     * Return mock for \Magento\ImportProducts\Model\Export class
     *
     * @return \Magento\ImportProducts\Model\Export
     */
    protected function _getMageImportProductsModelExportMock()
    {
        $this->_exportConfigMock = $this->getMock('Magento\ImportProducts\Model\Export\ConfigInterface');

        /** @var $abstractMockEntity \Magento\ImportProducts\Model\Export\AbstractEntity */
        $abstractMockEntity = $this->getMockForAbstractClass(
            'Magento\ImportProducts\Model\Export\AbstractEntity',
            [],
            '',
            false
        );

        /** @var $mockAdapterTest \Magento\ImportProducts\Model\Export\Adapter\AbstractAdapter */
        $mockAdapterTest = $this->getMockForAbstractClass(
            'Magento\ImportProducts\Model\Export\Adapter\AbstractAdapter',
            [],
            '',
            false,
            true,
            true,
            ['getFileExtension']
        );
        $mockAdapterTest->expects(
            $this->any()
        )->method(
            'getFileExtension'
        )->will(
            $this->returnValue($this->_exportFileExtension)
        );

        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $filesystem = $this->getMock('Magento\Framework\Filesystem', [], [], '', false);
        $entityFactory = $this->getMock(
            'Magento\ImportProducts\Model\Export\Entity\Factory',
            [],
            [],
            '',
            false
        );
        $exportAdapterFac = $this->getMock(
            'Magento\ImportProducts\Model\Export\Adapter\Factory',
            [],
            [],
            '',
            false
        );
        /** @var $mockModelExport \Magento\ImportProducts\Model\Export */
        $mockModelExport = $this->getMock(
            'Magento\ImportProducts\Model\Export',
            ['getEntityAdapter', '_getEntityAdapter', '_getWriter'],
            [$logger, $filesystem, $this->_exportConfigMock, $entityFactory, $exportAdapterFac]
        );
        $mockModelExport->expects(
            $this->any()
        )->method(
            'getEntityAdapter'
        )->will(
            $this->returnValue($abstractMockEntity)
        );
        $mockModelExport->expects(
            $this->any()
        )->method(
            '_getEntityAdapter'
        )->will(
            $this->returnValue($abstractMockEntity)
        );
        $mockModelExport->expects($this->any())->method('_getWriter')->will($this->returnValue($mockAdapterTest));

        return $mockModelExport;
    }

    /**
     * Test get file name with adapter file name
     */
    public function testGetFileNameWithAdapterFileName()
    {
        $model = $this->_getMageImportProductsModelExportMock();
        $basicFileName = 'test_file_name';
        $model->getEntityAdapter()->setFileName($basicFileName);

        $fileName = $model->getFileName();
        $correctDateTime = $this->_getCorrectDateTime($fileName);
        $this->assertNotNull($correctDateTime);

        $correctFileName = $basicFileName . '_' . $correctDateTime . '.' . $this->_exportFileExtension;
        $this->assertEquals($correctFileName, $fileName);
    }

    /**
     * Test get file name without adapter file name
     */
    public function testGetFileNameWithoutAdapterFileName()
    {
        $model = $this->_getMageImportProductsModelExportMock();
        $model->getEntityAdapter()->setFileName(null);
        $basicFileName = 'test_entity';
        $model->setEntity($basicFileName);

        $fileName = $model->getFileName();
        $correctDateTime = $this->_getCorrectDateTime($fileName);
        $this->assertNotNull($correctDateTime);

        $correctFileName = $basicFileName . '_' . $correctDateTime . '.' . $this->_exportFileExtension;
        $this->assertEquals($correctFileName, $fileName);
    }

    /**
     * Get correct file creation time
     *
     * @param string $fileName
     * @return string|null
     */
    protected function _getCorrectDateTime($fileName)
    {
        preg_match('/(\d{8}_\d{6})/', $fileName, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return null;
    }
}
