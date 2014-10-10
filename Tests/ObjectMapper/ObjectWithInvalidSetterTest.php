<?php
namespace Plum\Mapper\Tests\ObjectMapper\ObjectWithInvalidSetter
{
    use Plum\Mapper\Bind;

    class NoPrefix
    {
        /** @Bind */
        function invalidSetter($value)
        {

        }
    }

    class NoParameter
    {
        /** @Bind */
        function noParameter()
        {

        }
    }

    class MultipleParameters
    {
        /** @Bind */
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

    class ObjectWithInvalidSetterTest extends AbstractObjectMapperTest
    {
        /**
         * @test
         * @dataProvider provideInvalidSetterClasses
         * @expectedException \Plum\Mapper\ConfigurationException
         */
        function it_should_throws_when_setter_is_invalid($type)
        {
            $this->mapper->newInstance(NoPrefix::class, []);
        }

        function provideInvalidSetterClasses()
        {
            return [
                [NoPrefix::class],
                [NoParameter::class],
                [MultipleParameters::class],
            ];
        }
    }
}


