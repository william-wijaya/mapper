<?php
namespace Plum\Mapper\Impl\Mapping\Construction;

use Plum\Gen\CodeWriter;
use Plum\Mapper\Impl\Mapping\ConstructionMapping;
use Plum\Mapper\Impl\Mapping\DataMapping;

class NoArgMapping extends ConstructionMapping implements DataMapping
{
    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $w)
    {
        $w->write("new ", $this->name(), "()");
    }

    /**
     * {@inheritdoc}
     */
    public function compileGuards(CodeWriter $w)
    {
        //no guards
    }
}
