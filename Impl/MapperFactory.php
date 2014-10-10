<?php
namespace Plum\Mapper\Impl;

use Plum\Gen\ClassFileWriter;
use Plum\Gen\CodeSpace;
use Plum\Gen\CodeWriter;
use Plum\Reflect\Reflection;

class MapperFactory
{
    private $space;
    private $factory;
    private $reflection;


    public function __construct(
        CodeSpace $space, Reflection $reflection, MappingFactory $factory)
    {
        $this->space = $space;
        $this->factory = $factory;
        $this->reflection = $reflection;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    public function get($class)
    {
        $mapper = "ObjectMapper__".md5($class);
        if ($this->space->load($mapper))
            return $mapper;

        $mapping = $this->factory->get($class);

        $w = new CodeWriter();
        $cls = (new ClassFileWriter($w))
            ->beginClass(null, $mapper);

        $mapping->writeTo($cls);

        $cls->endClass();

        $this->space->save($mapper, $w);
        $this->space->load($mapper);

        return $mapper;
    }
} 
