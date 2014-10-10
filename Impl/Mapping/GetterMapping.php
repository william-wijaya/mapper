<?php
namespace Plum\Mapper\Impl\Mapping;

use Plum\Gen\CodeWriter;
use Plum\Mapper\Impl\Mapping;
use Plum\Reflect\Method;

class GetterMapping implements Mapping
{
    private $name;
    private $method;

    public function __construct($name, Method $method)
    {
        $this->name = $name;
        $this->method = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $w)
    {
        $w->literal($this->name);
        $w->write(' => ');
        $w->writeln('$i->', $this->method->name, "()");
    }
}
