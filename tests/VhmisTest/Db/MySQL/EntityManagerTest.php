<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Db\MySQL;

use Vhmis\Db\MySQL\EntityManager;

/**
 * EntityManager test
 */
class EntityManagerTest extends DatabaseTestCase
{
    public function testAll()
    {
        $entity = new GuestBookEntity;
        $entity->content = "haha";
        $entity->user = "anh";
        $entity->createdDate = "2016-12-22 12:22:22";
        $entity->setDb($this->getVhmisDb());
        
        $entity1 = new GuestBookEntity;
        $entity1->content = "hahadfhds fhd1";
        $entity1->user = "anh";
        $entity1->createdDate = "2016-12-22 12:22:22";
        $entity1->setDb($this->getVhmisDb());
        
        $db = $this->getVhmisDb();
        $em = new EntityManager($db);
        $em->forInsert($entity);
        $em->forInsert($entity1);
        
        $this->assertTrue($em->flush());
    }
    
    public function testAllForDb()
    {
        $entity = new GuestBookEntity;
        $entity->content = "haha1";
        $entity->user = "anh";
        $entity->createdDate = "2016-12-22 12:22:22";
        $entity->setDb($this->getVhmisDb());
        
        $entity1 = new GuestBookEntity;
        $entity1->content = "hahadfhds2";
        $entity1->user = "anh";
        $entity1->createdDate = "2016-12-22 12:22:22";
        $entity1->setDb($this->getVhmisDb());
        
        $db = $this->getVhmisDb();
        $db->entityForInsert($entity);
        $db->entityForInsert($entity1);
        
        $this->assertTrue($db->save());
        
        $entity->content = "New1";
        $entity1->content = "New2";
        $db->entityForUpdate($entity);
        $db->entityForUpdate($entity1);
        
        $this->assertTrue($db->save());
        
        $db->entityForDelete($entity);
        $db->entityForDelete($entity1);
        
        $this->assertTrue($db->save());
    }
}
