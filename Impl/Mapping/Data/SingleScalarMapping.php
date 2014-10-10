<?php
namespace Plum\Mapper\Impl\Mapping\Data;

use Plum\Gen\CodeWriter;

/**
 * Represents single argument mapping when the binding value is scalar
 */
class SingleScalarMapping extends MapperMapping
{
    private $var;
    private $name;

    public function __construct($name, VarMapping $var)
    {
        parent::__construct($name, $var);

        $this->var = $var;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $w)
    {
        $w->write("is_scalar(");
        $this->var->compile($w);
        $w->write(")");
        $w->indent();
        $w->write("? new ", $this->name, "(");
        $this->var->compile($w);
        $w->writeln(")");
        $w->write(": ");
        parent::compile($w);
        $w->outdent();
    }
}
