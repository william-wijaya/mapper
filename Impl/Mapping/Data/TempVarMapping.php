<?php
namespace Plum\Mapper\Impl\Mapping\Data;

use Plum\Gen\CodeWriter;

class TempVarMapping extends VarMapping
{
    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $w)
    {
        $w->write('$__v');
    }
}
