<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportProducts\Test\Unit\Model\Report;

class CsvTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\ImportProducts\Helper\Report|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $reportHelperMock;

    /**
     * @var \Magento\ImportProducts\Model\Export\Adapter\CsvFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $outputCsvFactoryMock;

    /**
     * @var \Magento\ImportProducts\Model\Export\Adapter\Csv|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $outputCsvMock;

    /**
     * @var \Magento\ImportProducts\Model\Import\Source\CsvFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $sourceCsvFactoryMock;

    /**
     * @var \Magento\ImportProducts\Model\Export\Adapter\Csv|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $sourceCsvMock;

    /**
     * @var \Magento\Framework\Filesystem|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $filesystemMock;

    /**
     * @var \Magento\ImportProducts\Model\Report\Csv|\Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $csvModel;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->reportHelperMock = $this->getMock('\Magento\ImportProducts\Helper\Report', [], [], '', false);

        $this->outputCsvFactoryMock = $this->getMock(
            '\Magento\ImportProducts\Model\Export\Adapter\CsvFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->outputCsvMock = $this->getMock('\Magento\ImportProducts\Model\Export\Adapter\Csv', [], [], '', false);
        $this->outputCsvFactoryMock->expects($this->any())->method('create')->willReturn($this->outputCsvMock);

        $this->sourceCsvFactoryMock = $this->getMock(
            '\Magento\ImportProducts\Model\Import\Source\CsvFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->sourceCsvMock = $this->getMock('\Magento\ImportProducts\Model\Import\Source\Csv', [], [], '', false);
        $this->sourceCsvMock->expects($this->any())->method('valid')->willReturnOnConsecutiveCalls(true, true, false);
        $this->sourceCsvMock->expects($this->any())->method('current')->willReturnOnConsecutiveCalls(
            [23 => 'first error'],
            [27 => 'second error']
        );
        $this->sourceCsvFactoryMock->expects($this->any())->method('create')->willReturn($this->sourceCsvMock);

        $this->filesystemMock = $this->getMock('\Magento\Framework\Filesystem', [], [], '', false);

        $this->csvModel = $objectManager->getObject(
            '\Magento\ImportProducts\Model\Report\Csv',
            [
                'reportHelper' => $this->reportHelperMock,
                'sourceCsvFactory' => $this->sourceCsvFactoryMock,
                'outputCsvFactory' => $this->outputCsvFactoryMock,
                'filesystem' => $this->filesystemMock
            ]
        );
    }

    public function testCreateReport()
    {
        $errorAggregatorMock = $this->getMock(
            'Magento\ImportProducts\Model\Import\ErrorProcessing\ProcessingErrorAggregator',
            [],
            [],
            '',
            false
        );
        $errorProcessingMock = $this->getMock(
            'Magento\ImportProducts\Model\Import\ErrorProcessing',
            ['getErrorMessage'],
            [],
            '',
            false
        );
        $errorProcessingMock->expects($this->any())->method('getErrorMessage')->willReturn('some_error_message');
        $errorAggregatorMock->expects($this->any())->method('getErrorByRowNumber')->willReturn([$errorProcessingMock]);
        $this->sourceCsvMock->expects($this->any())->method('getColNames')->willReturn([]);

        $name = $this->csvModel->createReport('some_file_name', $errorAggregatorMock, true);

        $this->assertEquals($name, 'some_file_name_error_report.csv');
    }
}
