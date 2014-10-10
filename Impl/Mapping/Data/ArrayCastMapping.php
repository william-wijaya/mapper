<?php
namespace Plum\Mapper\Impl\Mapping\Data;

use Plum\Gen\CodeWriter;
use Plum\Mapper\Impl\Mapping\DataMapping;

class ArrayCastMapping implements DataMapping
{
    private $data;

    public function __construct(DataMapping $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $w)
    {
        $w->write("(array)");
        $this->data->compile($w);
    }
}
