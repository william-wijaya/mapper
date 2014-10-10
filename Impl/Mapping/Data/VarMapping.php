<?php
namespace Plum\Mapper\Impl\Mapping\Data;

use Plum\Gen\CodeWriter;
use Plum\Mapper\Impl\Mapping\DataMapping;

class VarMapping implements DataMapping
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the var name
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $w)
    {
        $w->write('$b[');
        $w->literal($this->name());
        $w->write(']');
    }
}
