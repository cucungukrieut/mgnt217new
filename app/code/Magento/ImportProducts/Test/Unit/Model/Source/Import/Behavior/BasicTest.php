<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Test class for \Magento\ImportProducts\Model\Source\Import\Behavior\Basic
 */
namespace Magento\ImportProducts\Test\Unit\Model\Source\Import\Behavior;

class BasicTest extends \Magento\ImportProducts\Test\Unit\Model\Source\Import\AbstractBehaviorTestCase
{
    /**
     * Expected behavior group code
     *
     * @var string
     */
    protected $_expectedCode = 'basic';

    /**
     * Expected behaviours
     *
     * @var array
     */
    protected $_expectedBehaviors = [
        \Magento\ImportProducts\Model\Import::BEHAVIOR_APPEND,
        \Magento\ImportProducts\Model\Import::BEHAVIOR_REPLACE,
        \Magento\ImportProducts\Model\Import::BEHAVIOR_DELETE,
    ];

    protected function setUp()
    {
        parent::setUp();
        $this->_model = new \Magento\ImportProducts\Model\Source\Import\Behavior\Basic();
    }

    /**
     * Test toArray method
     *
     * @covers \Magento\ImportProducts\Model\Source\Import\Behavior\Basic::toArray
     */
    public function testToArray()
    {
        $behaviorData = $this->_model->toArray();
        $this->assertInternalType('array', $behaviorData);
        $this->assertEquals($this->_expectedBehaviors, array_keys($behaviorData));
    }

    /**
     * Test behavior group code
     *
     * @covers \Magento\ImportProducts\Model\Source\Import\Behavior\Basic::getCode
     */
    public function testGetCode()
    {
        $this->assertEquals($this->_expectedCode, $this->_model->getCode());
    }
}
