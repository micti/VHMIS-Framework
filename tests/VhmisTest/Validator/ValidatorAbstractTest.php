<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\ValidatorAbstract;

class ValidatorAbstractTest extends \PHPUnit_Framework_TestCase
{

    /**
     * ValidatorAbstract mock object
     *
     * @var ValidatorAbstract
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = $this->getMockForAbstractClass('\Vhmis\Validator\ValidatorAbstract');
    }

    public function testOptions()
    {
        $options = [
            'a' => 'b'
        ];

        $this->validator->setOptions($options);

        $this->assertSame([
            'allowNull' => false,
            'allowEmpty' => false,
            'a' => 'b'
                ], $this->validator->getOptions());

        $options = [
            'allowNull' => true
        ];

        $this->validator->setOptions($options);

        $this->assertSame([
            'allowNull' => true,
            'allowEmpty' => false,
            'a' => 'b'
        ], $this->validator->getOptions());

        $this->validator->reset();

        $this->assertSame([
            'allowNull' => false,
            'allowEmpty' => false
        ], $this->validator->getOptions());
    }
    
    public function testNullAndEmptyValue()
    {
        $this->assertSame(false, $this->validator->isValidForNullOrEmptyValue(null));
        $this->assertSame('Not value.', $this->validator->getMessage());
        $this->assertSame(1, $this->validator->getMessageCode());
        
        $this->assertSame(false, $this->validator->isValidForNullOrEmptyValue(''));
        $this->assertSame('Empty value.', $this->validator->getMessage());
        $this->assertSame(2, $this->validator->getMessageCode());
        
        $options = [
            'allowNull' => true,
            'allowEmpty' => true
        ];
        
        $this->validator->setOptions($options);
        
        $this->assertSame(true, $this->validator->isValidForNullOrEmptyValue(null));
        $this->assertSame(true, $this->validator->isValidForNullOrEmptyValue(''));
    }
    
    public function testLocaleOption()
    {
        locale_set_default('en_US');
        $this->validator->useLocaleOptions();
        $options = $this->validator->getOptions();
        $this->assertSame('en_US', $options['locale']);
    }
}
