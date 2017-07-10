<?php

/**
 * Vhmis Framework
 *
 * @link      http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\FolderName;

class FolderNameTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Validator object
     *
     * @var FolderName
     */
    protected $folderNameValidator;

    public function setUp()
    {
        $this->folderNameValidator = new FolderName();
    }

    public function testIsValid()
    {
        $this->assertSame(false, $this->folderNameValidator->isValid(null));
        $this->assertSame(false, $this->folderNameValidator->isValid(''));
        $this->assertSame(false, $this->folderNameValidator->isValid([]));
        $this->assertSame(false, $this->folderNameValidator->isValid(1));
        $this->assertSame(false, $this->folderNameValidator->isValid(-1));
        $this->assertSame(false, $this->folderNameValidator->isValid(1.0));
        $this->assertSame(true, $this->folderNameValidator->isValid('a'));
        $this->assertSame(true, $this->folderNameValidator->isValid('Công ty'));
        $this->assertSame(false, $this->folderNameValidator->isValid('a?fdfd'));
        $this->assertSame(false, $this->folderNameValidator->isValid('\a?fdfd'));
        $this->assertSame(false, $this->folderNameValidator->isValid('/a?fdfd'));
        $this->assertSame(false, $this->folderNameValidator->isValid('a:'));
        $this->assertSame(false, $this->folderNameValidator->isValid('a%a'));
        $this->assertSame(false, $this->folderNameValidator->isValid('a.a'));
    }

    public function testGetStandardValue()
    {
        $this->folderNameValidator->isValid('Công ty');
        $this->assertSame('Công ty', $this->folderNameValidator->getStandardValue());
    }
}
