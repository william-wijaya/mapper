<?php
namespace Plum\Mapper\Tests\ObjectMapper;

use Plum\Mapper\Bind;
use Plum\Mapper\Tests\AbstractObjectMapperTest;

class ObjectWithSetter
{
    public $value;

    /** @Bind */
    function setValue($value)
    {
        $this->value = $value;
    }


}

class ObjectWithSetterTest extends AbstractObjectMapperTest
{
    /** @test */
    function it_should_instantiate_object_with_setter()
    {
        $value = "instantiation value";

        $o = $this->mapper->newInstance(
            ObjectWithSetter::class, ["value" => $value]
        );

        $this->assertSame($value, $o->value);
    }

    /** @test */
    function it_should_bind_object_with_setter()
    {
        $value = "bind value";
        $o = new ObjectWithSetter();
        $this->mapper->bind($o, ["value" => $value]);

        $this->assertSame($value, $o->value);
    }
} 
