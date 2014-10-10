<?php
namespace Plum\Mapper\Impl\Mapping\Setter;

use Plum\Gen\CodeWriter;
use Plum\Mapper\Impl\Mapping\Data\TempVarMapping;
use Plum\Mapper\Impl\Mapping\Data\VarMapping;
use Plum\Mapper\Impl\Mapping\DataMapping;
use Plum\Mapper\Impl\Mapping\SetterMapping;
use Plum\Reflect\Method;

class MultiSetterMapping implements SetterMapping
{
    private $var;
    private $tmp;
    private $data;
    private $method;

    public function __construct(
        Method $method, VarMapping $var,
        TempVarMapping $tmp, DataMapping $data
    )
    {
        $this->var = $var;
        $this->tmp = $tmp;
        $this->data = $data;
        $this->method = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $w)
    {
        $w->write("if (isset(");
        $this->var->compile($w);
        $w->write(")) foreach ((array)");
        $this->var->compile($w);
        $w->write(" as ");
        $this->tmp->compile($w);
        $w->write(") {");
        $w->indent();
        $w->write('$i->', $this->method->name, "(");
        $this->data->compile($w);
        $w->write(");");
        $w->outdent();
        $w->write("}");
    }
}
