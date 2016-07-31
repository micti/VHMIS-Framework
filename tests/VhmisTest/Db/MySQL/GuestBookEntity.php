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
    protected $idKey = 'id';
    protected $tableName = 'guestbook';
    
    protected $fieldNameMap = array(
        'id'           => 'id',
        'content'      => 'content',
        'user'         => 'user',
        'created_date' => 'createdDate'
    );

    public $id;
    public $content;
    public $user;
    public $createdDate;
}
