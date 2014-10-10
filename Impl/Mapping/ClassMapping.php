<?php
namespace Plum\Mapper\Impl\Mapping;

use Plum\Gen\ClassWriter\ClassWriter;
use Plum\Gen\CodeWriter;
use Plum\Mapper\Impl\Mapping;
use Plum\Reflect\Method;

class ClassMapping
{
    private $construct;
    private $bind;
    private $reversion;

    public function __construct(
        ConstructionMapping $construct, BindMapping $bind,
        ReversionMapping $reversion)
    {
        $this->construct = $construct;
        $this->bind = $bind;
        $this->reversion = $reversion;
    }

    /**
     * @param ClassWriter $cls
     */
    public function writeTo(ClassWriter $cls)
    {
        $cls->method(Method::IS_STATIC, "newInstance")
            ->parameter("m")->parameter("b")
            ->body(function(CodeWriter $w) {
                $w->write('$i = ');
                $this->construct->compile($w);
                $w->writeln(";");

                if ($this->bind->setters())
                    $w->writeln('self::bind($m, $i, $b);');

                $w->write('return $i;');
            })
            ->method(Method::IS_STATIC, "bind")
            ->parameter("m")->parameter("i")->parameter("b")
            ->body(function(CodeWriter $w) {
                $this->bind->compile($w);
            })
            ->method(Method::IS_STATIC, "reverse")
            ->body(function(CodeWriter $w) {
                $w->write("return [");
                $this->reversion->compile($w);
                $w->write("];");
            });
    }
}
