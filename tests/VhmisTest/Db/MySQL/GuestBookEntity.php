<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Db\MySQL;

use Vhmis\Db\MySQL\Entity;

/**
 * GuestBookEntity for test
 */
class GuestBookEntity extends Entity
{

    protected $idName = 'id';
    protected $tableName = 'guestbook';
    protected $fieldNameMap = array(
        'id' => 'Id',
        'content' => 'content',
        'user' => 'user',
        'created_date' => 'createdDate'
    );
    protected $Id;
    public $content;
    public $user;
    public $createdDate;

}
