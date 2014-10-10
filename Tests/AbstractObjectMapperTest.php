<?php
namespace Plum\Mapper\Tests;

use Plum\Inject\Injector;
use Plum\Mapper\MapperModule;
use Plum\Mapper\ObjectMapper;

abstract class AbstractObjectMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectMapper
     */
    protected $mapper;

    /** @before */
    function setUp()
    {
        $i = Injector::create(null, null, MapperModule::class);

        $this->mapper = $i->getInstance(ObjectMapper::class);
    }
} 
