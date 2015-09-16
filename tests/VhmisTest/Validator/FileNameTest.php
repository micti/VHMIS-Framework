<?php

/**
 * Vhmis Framework
 *
 * @link      http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\FileName;

class FileNameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Validator object
     *
     * @var FileName
     */
    protected $fileNameValidator;

    public function setUp()
    {
        $this->fileNameValidator = new FileName();
    }

    public function testIsValid()
    {
        $this->assertSame(false, $this->fileNameValidator->isValid(null));
        $this->assertSame(false, $this->fileNameValidator->isValid(''));
        $this->assertSame(false, $this->fileNameValidator->isValid([]));
        $this->assertSame(false, $this->fileNameValidator->isValid(1));
        $this->assertSame(false, $this->fileNameValidator->isValid(-1));
        $this->assertSame(false, $this->fileNameValidator->isValid(1.0));
        $this->assertSame(true, $this->fileNameValidator->isValid('a'));
        $this->assertSame(true, $this->fileNameValidator->isValid('Công ty.pdf'));
        $this->assertSame(false, $this->fileNameValidator->isValid('a?fdfd'));
        $this->assertSame(false, $this->fileNameValidator->isValid('\a?fdfd'));
        $this->assertSame(false, $this->fileNameValidator->isValid('/a?fdfd'));
        $this->assertSame(false, $this->fileNameValidator->isValid('a:'));
        $this->assertSame(false, $this->fileNameValidator->isValid('a%a'));
        $this->assertSame(true, $this->fileNameValidator->isValid('a.txt'));
    }

    public function testGetStandardValue()
    {
        $this->fileNameValidator->isValid('Công ty.pdf');
        $this->assertSame('Công ty.pdf', $this->fileNameValidator->getStandardValue());
    }
}
