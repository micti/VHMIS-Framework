<?php

/**
 * Vhmis Framework
 *
 * @link      http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Validator;

/**
 * Filename validator.
 */
class FileName extends FolderName
{
    /**
     * Invalid characters in filename.
     *
     * @var string
     */
    protected $invalidCharacters = '\\?%*:|"<>\/';
}
