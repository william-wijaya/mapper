<?php
namespace Plum\Mapper;

interface ObjectMapper
{
    /**
     * @param string $typeName
     * @param array $binding
     * @param array,... $bindings
     *
     * @return object
     */
    public function newInstance($typeName, array $binding, array ...$bindings);

    /**
     * @param object $instance
     * @param array $binding
     * @param array,... $bindings
     */
    public function bind($instance, array $binding, array ...$bindings);

    /**
     * @param object $instance
     *
     * @return array
     */
    public function reverse($instance);
} 
