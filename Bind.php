<?php
namespace Plum\Mapper;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Bind
{
    /**
     * The binding name(s)
     *
     * @var mixed
     */
    public $value;

    /**
     * @var bool
     */
    public $multi = false;

    /**
     * @var bool
     */
    public $required = false;
} 
