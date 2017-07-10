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

class ValidatorAbstractTest extends \PHPUnit\Framework\TestCase
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
        $this->validator->setOptions(['a' => 'b']);
        $this->assertSame(['a' => 'b'], $this->validator->getOptions());

        $this->validator->setOptions(['a' => 'c']);
        $this->assertSame(['a' => 'c'], $this->validator->getOptions());

        $this->validator->reset();
        $this->assertSame([], $this->validator->getOptions());
    }
}
