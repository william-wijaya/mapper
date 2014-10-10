<?php
namespace Plum\Mapper\Impl\Mapping\Construction;

use Plum\Gen\CodeWriter;
use Plum\Mapper\Impl\Mapping;
use Plum\Mapper\Impl\Mapping\ConstructionMapping;

class NewOpMapping extends ConstructionMapping
{
    private $params;

    /**
     * @param string $name
     * @param ParameterMapping[] $params
     */
    public function __construct($name, array $params)
    {
        parent::__construct($name);

        $this->params = $params;
    }
    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $w)
    {
        $w->write("new ", $this->name(), "(");
        if (count($this->params) === 1) {
            reset($this->params)->compile($w);
        } else if (count($this->params) > 1) {
            $w->indent();
            $params = $this->params;
            array_shift($params)->compile($w);
            foreach ($params as $p) {
                $w->writeln(",");

                $p->compile($w);
            }
            $w->outdent();
        }
        $w->write(")");
    }

    /**
     * {@inheritdoc}
     */
    public function compileGuards(CodeWriter $w)
    {
        foreach ($this->params as $p) {
            $p->compileGuard($w);

            $w->nl();
        }
    }
}
