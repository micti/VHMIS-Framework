<?php

namespace VhmisTest\Container;

use PHPUnit\Framework\TestCase;
use Vhmis\Container\Container;
use Vhmis\Container\Param\Raw;
use VhmisTest\Container\{ABC, DEF};

class ContainerTest extends TestCase
{
    public function testSetAndGet()
    {
        $abc = new ABC(1, 2);
        $container = new Container();
        $container->setAlias('abc', $abc);
        $getABC = $container->get('abc');

        $this->assertSame($abc, $getABC);

        $container->set(ABC::class);
        $getABC = $container->get(ABC::class);
        $this->assertInstanceOf(ABC::class, $getABC);

        $service = $container->setAlias('abc', function($a, $c) { return $a + $c; }, true);
        $service->setParams([new Raw(1), new Raw(2)]);
        $getABC = $container->get('abc');
        $this->assertSame(3, $getABC);
        $service->setParams([new Raw(4), new Raw(2)]);
        $getABC = $container->get('abc');
        $this->assertSame(3, $getABC);

        $service = $container->setAlias('abc', function($a, $c) { return $a + $c; }, false);
        $service->setParams([new Raw(1), new Raw(2)]);
        $getABC = $container->get('abc');
        $this->assertSame(3, $getABC);
        $service->setParams([new Raw(4), new Raw(2)]);
        $getABC = $container->get('abc');
        $this->assertSame(6, $getABC);

//        $service = $container->setAlias('abc', ABC::class);
//        $service->addConstructorParams([new Raw(5), new Raw(6)]);
//        $getABC = $container->get('abc');
//        $this->assertSame(5, $getABC->a);
//        $this->assertSame(6, $getABC->b);
    }

    public function testHas()
    {
        $container = new Container();
        $this->assertTrue($container->has('VhmisTest\Container\ABC'));
        $this->assertFalse($container->has('VhmisTest\Container\Nothing')); // no autoload
        $this->assertFalse($container->has('a'));
    }
}
