<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Db\MySQL;

/**
 * Entity test
 */
class EntityTest extends DatabaseTestCase
{

    public function testStatus()
    {
        $entity = new GuestBookEntity;
        $entity->setDb($this->getVhmisDb());
        $this->assertEquals(GuestBookEntity::STATE_NEW, $entity->getState());

        $this->assertFalse($entity->insert());
        $this->assertFalse($entity->update());
        $this->assertFalse($entity->delete());
    }

    public function testInsert()
    {
        $entity = new GuestBookEntity;
        $entity->content = "haha";
        $entity->user = "anh";
        $entity->createdDate = "2016-12-22 12:22:22";
        $entity->setDb($this->getVhmisDb());

        $this->assertTrue($entity->insert());
        $this->assertEquals(1, $entity->getIdValue());

        $entity1 = new GuestBookEntity;
        $entity1->content = "hahadfhds fhds fhsdf hdsj fhds fhdsf dsjhf sdhf sdhjf sdhjf sdhjf sdhf sdhfjds";
        $entity1->user = "anh";
        $entity1->createdDate = "2016-12-22 12:22:22";
        $entity1->setDb($this->getVhmisDb());
        $this->assertTrue($entity1->insert());

        $entity->content = "fdjdsj dsfdsghf";
        $this->assertTrue($entity->save());

        $this->assertTrue($entity->delete());
        $this->assertNull($entity->getIdValue());
        $this->assertEquals(GuestBookEntity::STATE_DELETED, $entity->getState());
    }
}
