<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\ValidatorChain;

class ValidatorChainTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Validator chain
     *
     * @var ValidatorChain
     */
    protected $validatorChain;

    public function setUp()
    {
        if (!extension_loaded('intl')) {
            $this->markTestSkipped(
                    'Intl ext is not available.'
            );
        }

        if (!class_exists('\IntlCalendar')) {
            $this->markTestSkipped(
                    'Intl version 3.0.0 is not available.'
            );
        }

        locale_set_default('en_US');
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->validatorChain = new ValidatorChain();
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testNotValidValidator()
    {
        $this->validatorChain->addValidator('a', 'wrong');
    }

    public function testAdd()
    {
        $this->validatorChain->addValidator('a', 'Int');
        $fields = [
            'a' => [
                'value' => null,
                'validator' => [
                    'Int' => []
                ]
            ]
        ];
        $this->assertEquals($fields, $this->validatorChain->getFields());

        $this->validatorChain->addValidator('a', 'DateTime', ['pattern' => 'mm-dd-Y']);
        $fields = [
            'a' => [
                'value' => null,
                'validator' => [
                    'Int' => [],
                    'DateTime' => ['pattern' => 'mm-dd-Y']
                ]
            ]
        ];
        $this->assertEquals($fields, $this->validatorChain->getFields());

        $this->validatorChain->addValidator('a', 'DateTime', ['pattern' => 'dd-mm-Y']);
        $fields = [
            'a' => [
                'value' => null,
                'validator' => [
                    'Int' => [],
                    'DateTime' => ['pattern' => 'dd-mm-Y']
                ]
            ]
        ];
        $this->assertEquals($fields, $this->validatorChain->getFields());

        $this->validatorChain->addValue('a', 'bbb');
        $fields = [
            'a' => [
                'value' => 'bbb',
                'validator' => [
                    'Int' => [],
                    'DateTime' => ['pattern' => 'dd-mm-Y']
                ]
            ]
        ];
        $this->assertEquals($fields, $this->validatorChain->getFields());

        $this->validatorChain->addValue('c', '111bbb');
        $fields = [
            'a' => [
                'value' => 'bbb',
                'validator' => [
                    'Int' => [],
                    'DateTime' => ['pattern' => 'dd-mm-Y']
                ]
            ],
            'c' => [
                'value' => '111bbb',
                'validator' => []
            ]
        ];
        $this->assertEquals($fields, $this->validatorChain->getFields());

        $this->validatorChain->reset();
        $this->assertEquals([], $this->validatorChain->getFields());

        $this->validatorChain->addValidator('a', 'Int');
        $this->validatorChain->addValidator('b', 'Int');

        $values = [
            'a' => 1,
            'b' => 2,
            'c' => 3
        ];

        $this->validatorChain->fill($values);
        $fields = [
            'a' => [
                'value' => 1,
                'validator' => [
                    'Int' => []
                ]
            ],
            'b' => [
                'value' => 2,
                'validator' => [
                    'Int' => []
                ]
            ]
        ];
        $this->assertEquals($fields, $this->validatorChain->getFields());
    }

    public function testValid()
    {
        $this->validatorChain->reset();

        $this->validatorChain->addValidator('a', 'Int');
        $this->validatorChain->addValue('a', '89');
        $this->validatorChain->addValidator('b', 'DateTime', ['pattern' => 'M/d/yy']);
        $this->validatorChain->addValue('b', '12/12/2014');
        $this->assertTrue($this->validatorChain->isValid());
        $standardValues = $this->validatorChain->getStandardValues();
        $this->assertTrue(is_int($standardValues['a']));
        $this->assertTrue($standardValues['b'] instanceof \Vhmis\I18n\DateTime\DateTime);
    }

    public function testNotValid()
    {
        $this->validatorChain->reset();

        $this->validatorChain->addValidator('a', 'Int');
        $this->validatorChain->addValidator('a', 'Greater', ['compare' => 100]);
        $this->validatorChain->addValue('a', '89');
        $this->validatorChain->addValidator('b', 'DateTime', ['pattern' => 'M/d/yy']);
        $this->validatorChain->addValue('b', '12/12/2014');
        $this->assertFalse($this->validatorChain->isValid());
        $this->assertEquals('a', $this->validatorChain->getNotValidField());
        $this->assertEquals('The given value is equal or smaller than compared value.', $this->validatorChain->getNotValidMessage());
        $this->assertEquals(\Vhmis\Validator\Greater::E_EQUAL_OR_SMALLER, $this->validatorChain->getNotValidCode());
    }

    public function testSkippedField()
    {
        $this->validatorChain->reset();

        $this->validatorChain->addValidator('a', 'Int');
        $this->validatorChain->addValidator('a', 'Greater', ['compare' => 100]);
        $this->validatorChain->addValue('a', '89');
        $this->validatorChain->addValidator('b', 'DateTime', ['pattern' => 'M/d/yy']);
        $this->validatorChain->addValue('b', '12/12/2014');
        $this->assertTrue($this->validatorChain->isValid(['a']));
    }
}
