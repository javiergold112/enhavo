<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-20
 * Time: 08:47
 */

namespace Enhavo\Bundle\RoutingBundle\Tests\AutoGenerator\Generator;

use Enhavo\Bundle\AppBundle\Repository\EntityRepositoryInterface;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator\PrefixGenerator;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Tests\Mock\RouteContentMock;
use PHPUnit\Framework\TestCase;

class PrefixGeneratorTest extends TestCase
{
    private function createResource()
    {
        $resource = new RouteContentMock();
        $resource->setRoute(new Route());
        $resource->setTitle('this is a title');
        $resource->setSubTitle('My subtitle');
        return $resource;
    }

    private function createRepository(array $prefixes = [])
    {
        $repository = $this->getMockBuilder(EntityRepositoryInterface::class)->getMock();
        $repository->method('findBy')->willReturnCallback(function ($criteria) use ($prefixes) {
            if(isset($criteria['staticPrefix']) && in_array($criteria['staticPrefix'], $prefixes)) {
                return [new Route()];
            }
            return [];
        });
        return $repository;
    }

    public function testSingleProperty()
    {
        $repository = $this->createRepository();
        $resource = $this->createResource();

        $generator = new Generator(new PrefixGenerator($repository), [
            'properties' => 'title'
        ], $resource);

        $generator->generate();

        $this->assertEquals('/this-is-a-title', $resource->getRoute()->getStaticPrefix());
    }

    public function testMultipleProperty()
    {
        $repository = $this->createRepository();
        $resource = $this->createResource();

        $generator = new Generator(new PrefixGenerator($repository), [
            'properties' => ['title', 'subTitle'],
        ], $resource);

        $generator->generate();

        $this->assertEquals('/this-is-a-title-my-subtitle', $resource->getRoute()->getStaticPrefix());
    }

    public function testFormat()
    {
        $repository = $this->createRepository();
        $resource = $this->createResource();

        $generator = new Generator(new PrefixGenerator($repository), [
            'properties' => ['title', 'subTitle'],
            'format' => '/{subTitle}/{title}/'
        ], $resource);

        $generator->generate();

        $this->assertEquals('/my-subtitle/this-is-a-title/', $resource->getRoute()->getStaticPrefix());
    }

    public function testUnique()
    {
        $repository = $this->createRepository(['/this-is-a-title', '/this-is-a-title-1']);
        $resource = $this->createResource();

        $generator = new Generator(new PrefixGenerator($repository), [
            'properties' => 'title',
            'unique' => true
        ], $resource);

        $generator->generate();

        $this->assertEquals('/this-is-a-title-2', $resource->getRoute()->getStaticPrefix());
    }

    public function testUniqueWithEndingNumber()
    {
        $repository = $this->createRepository(['/12-12-12']);
        $resource = $this->createResource();
        $resource->setTitle('12 12 12');

        $generator = new Generator(new PrefixGenerator($repository), [
            'properties' => 'title',
            'unique' => true
        ], $resource);

        $generator->generate();

        $this->assertEquals('/12-12-12-1', $resource->getRoute()->getStaticPrefix());
    }

    public function testMultipleUnique()
    {
        $repository = $this->createRepository(['/this-is-a-title-my-subtitle']);
        $resource = $this->createResource();

        $generator = new Generator(new PrefixGenerator($repository), [
            'properties' => ['title', 'subTitle'],
            'unique' => true,
        ], $resource);

        $generator->generate();

        $this->assertEquals('/this-is-a-title-my-subtitle-1', $resource->getRoute()->getStaticPrefix());
    }

    public function testMultipleUniqueWithFormat()
    {
        $repository = $this->createRepository(['/this-is-a-title/my-subtitle/']);
        $resource = $this->createResource();

        $generator = new Generator(new PrefixGenerator($repository), [
            'properties' => ['title', 'subTitle'],
            'unique' => true,
            'format' => '/{title}/{subTitle}/'
        ], $resource);

        $generator->generate();

        $this->assertEquals('/this-is-a-title/my-subtitle/-1', $resource->getRoute()->getStaticPrefix());
    }

    public function testMultipleUniqueWithUniqueProperty()
    {
        $repository = $this->createRepository(['/this-is-a-title/my-subtitle']);
        $resource = $this->createResource();

        $generator = new Generator(new PrefixGenerator($repository), [
            'properties' => ['title', 'subTitle'],
            'unique' => true,
            'unique_property' => 'title',
            'format' => '/{title}/{subTitle}'
        ], $resource);

        $generator->generate();

        $this->assertEquals('/this-is-a-title-1/my-subtitle', $resource->getRoute()->getStaticPrefix());
    }

    public function testOverwriteTrue()
    {
        $repository = $this->createRepository();
        $resource = $this->createResource();
        $resource->getRoute()->setStaticPrefix('/exists');

        $generator = new Generator(new PrefixGenerator($repository), [
            'properties' => 'title',
            'overwrite' => true
        ], $resource);

        $generator->generate();

        $this->assertEquals('/this-is-a-title', $resource->getRoute()->getStaticPrefix());
    }

    public function testOverwriteFalse()
    {
        $repository = $this->createRepository();
        $resource = $this->createResource();
        $resource->getRoute()->setStaticPrefix('/exists');

        $generator = new Generator(new PrefixGenerator($repository), [
            'properties' => 'title',
            'overwrite' => false
        ], $resource);

        $generator->generate();

        $this->assertEquals('/exists', $resource->getRoute()->getStaticPrefix());
    }

    public function testUniquePropertyMismatch()
    {
        $repository = $this->createRepository();
        $resource = $this->createResource();

        $generator = new Generator(new PrefixGenerator($repository), [
            'properties' => 'title',
            'unique' => true,
            'unique_property' => 'subTitle',
        ], $resource);

        $this->expectException(\InvalidArgumentException::class);
        $generator->generate();
    }
}
