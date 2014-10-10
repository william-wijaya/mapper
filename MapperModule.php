<?php
namespace Plum\Mapper;

use Plum\Inject\Provides;
use Plum\Inject\Singleton;
use Plum\Mapper\Impl\ObjectMapperImpl;

class MapperModule
{
    /** @Provides(ObjectMapper::class) @Singleton */
    public static function provideObjectMapper(ObjectMapperImpl $impl)
    {
        return $impl;
    }
} 
