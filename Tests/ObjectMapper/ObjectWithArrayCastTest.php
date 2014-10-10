<?php
namespace Plum\Mapper\Tests\ObjectMapper;

use Plum\Mapper\Bind;
use Plum\Mapper\Tests\AbstractObjectMapperTest;

class ObjectWithArrayCastConstructor
{
    public $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }
}

class ObjectWithArrayCastSetter
{
    public $values;

    /** @Bind */
    public function setValues(array $values)
    {
        $this->values = $values;
    }
}

class ObjectWithArrayCastTest extends AbstractObjectMapperTest
{
    /** @test */
    function it_should_instantiate_object_with_array_constructor_parameter()
    {
        $value = "constructor value";

        $o = $this->mapper->newInstance(
            ObjectWithArrayCastConstructor::class, ["values" => $value]
        );

        $this->assertEquals((array)$value, $o->values);
    }

    /** @test */
    function it_should_instantiate_object_with_array_setter_parameter()
    {
        $value = "setter value";

        $o = new ObjectWithArrayCastSetter();
        $this->mapper->bind($o, ["values" => $value]);

        $this->assertEquals((array)$value, $o->values);
    }
} 
