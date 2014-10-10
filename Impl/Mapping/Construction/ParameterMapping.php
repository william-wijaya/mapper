<?php
namespace Plum\Mapper\Impl\Mapping\Construction;

use Plum\Gen\CodeWriter;
use Plum\Mapper\Impl\Mapping;
use Plum\Mapper\Impl\Mapping\DataMapping;
use Plum\Mapper\Impl\Mapping\Data\VarMapping;
use Plum\Mapper\MappingException;
use Plum\Reflect\Parameter;

class ParameterMapping implements Mapping
{
    private $var;
    private $data;
    private $param;

    public function __construct(
        VarMapping $var, DataMapping $data, Parameter $param
    )
    {
        $this->var = $var;
        $this->data = $data;
        $this->param = $param;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $writer)
    {
        $this->data->compile($writer);
    }

    /**
     * Compiles constructor parameter guard
     *
     * @param CodeWriter $writer
     */
    public function compileGuard(CodeWriter $writer)
    {
        $writer->write("if (!isset(");
        $this->var->compile($writer);
        $writer->write(")) throw new ", MappingException::class, "(");
        $writer->indent();
        $writer->literal(
            "Missing required data \"".$this->var->name()."\" for ".
            $this->param->getDeclaringClass()->name."::__construct".
            "(\${$this->param->name})"
        );
        $writer->outdent();
        $writer->write(");");
    }
}
