<?php
namespace Plum\Mapper\Tests\ObjectMapper;

use Plum\Mapper\Bind;
use Plum\Mapper\Tests\AbstractObjectMapperTest;

class ObjectWithMultiSetter
{
    public $values = [];

    /** @Bind(multi = true) */
    function addValues($value)
    {
        $this->values[] = $value;
    }
}

class ObjectWithMultiSetterTest extends AbstractObjectMapperTest
{
    /** @test */
    function it_should_instantiate_object_with_multi_setter()
    {
        $values = [1, new \stdClass(), []];

        $o = $this->mapper->newInstance(
            ObjectWithMultiSetter::class,
            ["values" => $values]
        );

        $this->assertEquals($values, $o->values);
    }

    /** @test */
    function it_should_bind_object_with_multi_setter()
    {
        $values = [1, new \stdClass(), []];

        $o = new ObjectWithMultiSetter();
        $this->mapper->bind($o, ["values" => $values]);

        $this->assertEquals($values, $o->values);
    }
} 
