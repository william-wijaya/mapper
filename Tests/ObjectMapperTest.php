<?php
namespace Plum\Mapper\Tests;

use Plum\Inject\Injector;
use Plum\Mapper\MapperModule;
use Plum\Mapper\ObjectMapper;

class ObjectMapperTest extends AbstractObjectMapperTest
{
    /** @test */
    function it_should_returns_no_arg_instance()
    {
        $this->assertInternalType(
            "object",
            $this->mapper->newInstance(\stdClass::class, [])
        );
    }

    /** @test */
    function it_should_returns_instance()
    {
        $array = [1, 2, 3];
        $o = $this->mapper->newInstance(\ArrayObject::class, [
            "array" => $array,
        ]);

        $this->assertEquals($array, iterator_to_array($o));
    }
} 
