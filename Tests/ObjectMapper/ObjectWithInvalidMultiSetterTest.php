<?php
namespace Plum\Mapper\Tests\ObjectMapper\ObjectWithInvalidMultiSetter
{
    use Plum\Mapper\Bind;

    class NoPrefix
    {
        /** @Bind(multi = true) */
        function invalidSetter($value)
        {

        }
    }

    class NoParameter
    {
        /** @Bind(multi = true) */
        function noParameter()
        {

        }
    }

    class MultipleParameters
    {
        /** @Bind(multi = true) */
        function multipleParameters($v1, $v2, $v3)
        {

        }
    }
}

namespace Plum\Mapper\Tests\ObjectMapper
{
    use Plum\Mapper\Tests\AbstractObjectMapperTest;
    use Plum\Mapper\Tests\ObjectMapper\ObjectWithInvalidSetter\MultipleParameters;
    use Plum\Mapper\Tests\ObjectMapper\ObjectWithInvalidSetter\NoParameter;
    use Plum\Mapper\Tests\ObjectMapper\ObjectWithInvalidSetter\NoPrefix;

    class ObjectWithInvalidMultiSetterTest extends AbstractObjectMapperTest
    {
        /**
         * @test
         * @dataProvider provideInvalidMultiSetterClass
         * @expectedException \Plum\Mapper\ConfigurationException
         */
        function it_should_throws_when_instantiating_object_with_invalid_multi_setter($type)
        {
            $this->mapper->newInstance($type, []);
        }

        function provideInvalidMultiSetterClass()
        {
            return [
                [NoPrefix::class],
                [NoParameter::class],
                [MultipleParameters::class]
            ];
        }
    }
}


