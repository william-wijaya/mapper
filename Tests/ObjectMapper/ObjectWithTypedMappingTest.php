<?php
namespace Plum\Mapper\Tests\ObjectMapper;

use Plum\Mapper\Bind;
use Plum\Mapper\Tests\AbstractObjectMapperTest;

class ObjectWithTypedConstructor
{
    public $object;

    public function __construct(\stdClass $object)
    {
        $this->object = $object;
    }
}

class ObjectWithTypedSetter
{
    public $object;

    /** @Bind */
    public function setObject(ObjectWithTypedConstructor $object)
    {
        $this->object = $object;
    }
}

class ObjectWithTypedMappingTest extends AbstractObjectMapperTest
{
    /** @test */
    function it_should_instantiate_object_with_typed_constructor()
    {
        $o = $this->mapper->newInstance(
            ObjectWithTypedConstructor::class, []
        );

        $this->assertInstanceOf(\stdClass::class, $o->object);
    }

    /** @test */
    function it_should_bind_object_with_typed_setter()
    {
        $o = new ObjectWithTypedSetter();

        $this->mapper->bind($o, ["object" => []]);

        $this->assertInstanceOf(ObjectWithTypedConstructor::class, $o->object);
    }
} 
