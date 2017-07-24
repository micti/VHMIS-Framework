<?php

namespace VhmisTest\Container;

use PHPUnit\Framework\TestCase;
use Vhmis\Container\Container;
use Vhmis\Container\Param\Raw;
use Vhmis\Container\Param\Service;
use VhmisTest\Container\{ABC, DEF, Class1};

class ContainerTest extends TestCase
{
    public function testSetAndGet()
    {
        $abc = new ABC(1, 2);
        $container = new Container();
        $container->setAlias('abc', $abc);
        $getABC = $container->get('abc');

        $this->assertSame($abc, $getABC);

        $container->set(Class1::class);
        $getClass1 = $container->get(Class1::class);
        $this->assertInstanceOf(Class1::class, $getClass1);

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

        $service = $container->setAlias('abc', ABC::class);
        $service->setConstructorParams([new Raw(5), new Raw(6)]);
        $getABC = $container->get('abc');
        $this->assertSame(5, $getABC->a);
        $this->assertSame(6, $getABC->b);
        $this->assertSame(2, $getABC->c);

        $service = $container->setAlias('abc1', ABC::class);
        $service->setConstructorParams([new Raw(5), new Raw(6)]);
        $service->setMethod('setC', [new Raw(9)]);
        $getABC = $container->get('abc1');
        $this->assertSame(5, $getABC->a);
        $this->assertSame(6, $getABC->b);
        $this->assertSame(9, $getABC->c);

        $this->assertFalse(is_object(1));
        $this->assertFalse($container->setAlias('a', 1));
        $this->assertFalse($container->setAlias('a', 1.3));
    }

    public function testHas()
    {
        $container = new Container();
        $this->assertTrue($container->has('VhmisTest\Container\ABC'));
        $this->assertFalse($container->has('VhmisTest\Container\Nothing')); // no autoload
        $this->assertFalse($container->has('a'));
    }

    public function testParamFromContainerService()
    {
        $container = new Container();
        $container->set(Class1::class);
        $param = new Service(Class1::class);
        $param->setContainer($container);
        $this->assertInstanceOf(Class1::class, $param->getValue());

        $param = new Service('a');
        $this->assertNull($param->setContainer($container)->getValue());
    }

    public function testConstructorWithServiceParam()
    {
        $container = new Container();
        $abcService = $container->setAlias('abc', ABC::class);
        $abcService->setConstructorParams([new Raw(1), new Raw(2)]);
        $defService = $container->setAlias('def', DEF::class);
        $defService->setConstructorParams([new Service('abc')]);

        $this->assertInstanceOf(ABC::class, $container->get('def')->a);
        $this->assertSame(1, $container->get('def')->a->a);
        $this->assertSame(2, $container->get('def')->a->b);
        $this->assertSame(2, $container->get('def')->a->c);
    }

    public function testMethodWithNoParam()
    {
        $container = new Container();
        $container->setAlias('a', function() {
            return new \VhmisTest\Container\Class1;
        });

        $this->assertSame('1', $container->get('a')->echoMe());

        $container->setAlias('c', Class1::class)->setMethod('helloInVietnamese', []);

        $this->assertSame('Xin chao', $container->get('c')->helloMe());
    }
}
