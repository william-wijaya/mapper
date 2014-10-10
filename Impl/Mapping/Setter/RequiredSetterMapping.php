<?php
namespace Plum\Mapper\Impl\Mapping\Setter;

use Plum\Gen\CodeWriter;
use Plum\Mapper\ConfigurationException;
use Plum\Mapper\Impl\Mapping\Data\VarMapping;
use Plum\Mapper\Impl\Mapping\SetterMapping;

class RequiredSetterMapping implements SetterMapping
{
    private $var;
    private $setter;

    public function __construct(VarMapping $var, SetterMapping $setter)
    {
        $this->var = $var;
        $this->setter = $setter;
    }

    /**
     * {@inheritdoc}
     */
    public function method()
    {
        return $this->setter->method();
    }

    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $w)
    {
        $m = $this->method();

        $this->setter->compile($w);
        $w->write("else throw new ", ConfigurationException::class, "(");
        $w->indent();
        $w->literal(
            "Missing required data \"".$this->var->name()."\" for ".
            "{$m->class}::{$m->name}()"
        );
        $w->outdent();
        $w->writeln(");");
    }
}
