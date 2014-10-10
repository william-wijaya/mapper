<?php
namespace Plum\Mapper\Impl\Mapping\Data;

use Plum\Gen\CodeWriter;
use Plum\Mapper\Impl\Mapping\DataMapping;

class MapperMapping implements DataMapping
{
    private $data;
    private $class;

    public function __construct($class, VarMapping $var)
    {
        $this->data = $var;
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $w)
    {
        $w->write('$m->newInstance(', $this->class, '::class, ');
        $this->data->compile($w);
        $w->write(')');
    }
}
