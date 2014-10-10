<?php
namespace Plum\Mapper\Impl;

use Plum\Mapper\ObjectMapper;

class ObjectMapperImpl implements ObjectMapper
{
    private $factory;

    public function __construct(MapperFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function newInstance($typeName, array $binding, array ...$bindings)
    {
        $m = $this->factory->get($typeName);
        $b = array_merge($binding, ...$bindings);

        return $m::newInstance($this, $b);
    }

    /**
     * {@inheritdoc}
     */
    public function bind($instance, array $binding, array ...$bindings)
    {
        $m = $this->factory->get(get_class($instance));
        $b = array_merge($binding, ...$bindings);

        $m::bind($this, $instance, $b);
    }

    /**
     * {@inheritdoc}
     */
    public function reverse($instance)
    {
        $m = $this->factory->get(get_class($instance));

        return $m::reverse($instance);
    }
}
