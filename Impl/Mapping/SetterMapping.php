<?php
namespace Plum\Mapper\Impl\Mapping;

use Plum\Mapper\Impl\Mapping;
use Plum\Reflect\Method;

interface SetterMapping extends Mapping
{
    /**
     * Returns the setter method
     *
     * @return Method
     */
    public function method();
} 
