<?php
namespace Plum\Mapper\Impl\Mapping;

use Plum\Gen\CodeWriter;
use Plum\Mapper\Impl\Mapping;

abstract class ConstructionMapping implements Mapping
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the class name
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Compiles guards code for checking the bindings
     *
     * @param CodeWriter $writer
     */
    public abstract function compileGuards(CodeWriter $writer);
}
