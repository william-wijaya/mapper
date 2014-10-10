<?php
namespace Plum\Mapper\Impl;

use Plum\Mapper\Bind;
use Plum\Mapper\ConfigurationException;
use Plum\Mapper\Expose;
use Plum\Mapper\Impl\Mapping\BindMapping;
use Plum\Mapper\Impl\Mapping\ClassMapping;
use Plum\Mapper\Impl\Mapping\Construction\NewOpMapping;
use Plum\Mapper\Impl\Mapping\Construction\NoArgMapping;
use Plum\Mapper\Impl\Mapping\Construction\ParameterMapping;
use Plum\Mapper\Impl\Mapping\Data\SingleScalarMapping;
use Plum\Mapper\Impl\Mapping\ConstructionMapping;
use Plum\Mapper\Impl\Mapping\Data\ArrayCastMapping;
use Plum\Mapper\Impl\Mapping\Data\VarMapping;
use Plum\Mapper\Impl\Mapping\Data\MapperMapping;
use Plum\Mapper\Impl\Mapping\Data\TempVarMapping;
use Plum\Mapper\Impl\Mapping\DataMapping;
use Plum\Mapper\Impl\Mapping\GetterMapping;
use Plum\Mapper\Impl\Mapping\ReversionMapping;
use Plum\Mapper\Impl\Mapping\Setter\MultiSetterMapping;
use Plum\Mapper\Impl\Mapping\Setter\SetterMappingImpl;
use Plum\Mapper\Impl\Mapping\SetterMapping;
use Plum\Reflect\Method;
use Plum\Reflect\Parameter;
use Plum\Reflect\Reflection;
use Plum\Reflect\Type;

class MappingFactory
{
    private $reflection;

    public function __construct(Reflection $reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * @param string $class
     *
     * @return ClassMapping
     */
    public function get($class)
    {
        $t = $this->reflection->ofType($class);

        $c = $this->mapConstructor($t);
        $b = $this->mapBind($t);
        $r = $this->mapReversion($t);

        return new ClassMapping($c, $b, $r);
    }

    /**
     * @param Type $type
     *
     * @return ConstructionMapping
     */
    public function mapConstructor(Type $type)
    {
        $c = $type->getConstructor();
        if (!$c || !$c->getNumberOfParameters())
            return new NoArgMapping($type->name);

        $params = [];
        foreach ($c->getParameters() as $p) {
            $b = $p->getAnnotation(Bind::class);
            if ($b && $b->value)
                $name = $b->value;
            else
                $name = $p->name;

            $v = new VarMapping($name);
            $d = $this->mapData($p, $v);

            $params[] = new ParameterMapping($v, $d, $p);
        }

        return new NewOpMapping($type->name, $params);
    }

    /**
     * @param Type $type
     *
     * @return BindMapping
     */
    public function mapBind(Type $type)
    {
        $setters = [];
        foreach ($type->getMethods(Method::IS_PUBLIC) as $m) {
            $b = $m->getAnnotation(Bind::class);
            if (!$b) continue;

            $nop = $m->getNumberOfParameters();
            if ($nop !== 1) throw new ConfigurationException(
                "Setter method must exactly have 1 parameter, ".
                "{$m->class}::{$m->name}() have {$nop}"
            );

            if ($b->multi)
                $setters[] = $this->mapMultiSetter($m);
            else
                $setters[] = $this->mapSetter($m);
        }

        return new BindMapping($setters);
    }

    /**
     * @param Method $method
     *
     * @return SetterMapping
     */
    public function mapSetter(Method $method)
    {
        $b = $method->getAnnotation(Bind::class);
        $p = $method->getParameters()[0];
        if ($b->value)
            $name = $b->value;
        else if (stripos($method->name, "set") === 0)
            $name = lcfirst(substr($method->name, 3));
        else throw new ConfigurationException(
            "Invalid setter ".$method->getType()->name."::{$method->name}() ".
            ', either add name to @Bind("<name>") annotation or use "set" '.
            'prefix for the method'
        );

        $v = new VarMapping($name);
        $d = $this->mapData($p, $v);

        return new SetterMappingImpl($method, $v, $d);
    }

    /**
     * @param Method $method
     *
     * @return MultiSetterMapping
     */
    public function mapMultiSetter(Method $method)
    {
        $b = $method->getAnnotation(Bind::class);
        $p = $method->getParameters()[0];
        if ($b->value)
            $name = $b->value;
        else if (stripos($method->name, "add") === 0)
            $name = lcfirst(substr($method->name, 3));
        else throw new ConfigurationException(
            "Invalid setter ".$method->getType()->name."::{$method->name}() ".
            ', either add name to @Bind("<name>") annotation or use "add" '.
            'prefix for the method'
        );

        $v = new VarMapping($name);
        $t = new TempVarMapping($name);
        $d = $this->mapData($p, $t);

        return new MultiSetterMapping($method, $v, $t, $d);
    }

    /**
     * @param Type $type
     *
     * @return ReversionMapping
     */
    public function mapReversion(Type $type)
    {
        $getters = [];
        foreach ($type->getMethods(Method::IS_PUBLIC) as $m) {
            $e = $m->getAnnotation(Expose::class);
            if (!$e) continue;

            $nop = $m->getNumberOfParameters();
            if ($nop) throw new ConfigurationException(
                "Getter method must not have parameter(s), ".
                "{$m->class}::{$m->name}() have {$nop}"
            );

            $getters[] = $this->mapGetter($m);
        }

        return new ReversionMapping($getters);
    }

    /**
     * @param Method $method
     *
     * @return GetterMapping
     */
    public function mapGetter(Method $method)
    {
        $e = $method->getAnnotation(Expose::class);
        if ($e->value)
            $name = $e->value;
        else if (stripos($method->name, "get") === 0)
            $name = lcfirst(substr($method->name, 3));
        else
            $name = $method->name;

        return new GetterMapping($name, $method);
    }

    /**
     * @param Parameter $param
     * @param VarMapping $var
     *
     * @return DataMapping
     */
    public function mapData(Parameter $param, VarMapping $var)
    {
        if ($param->isArray())
            return new ArrayCastMapping($var);

        $t = $param->getClass();
        if (!$t) return $var;

        if (self::canBeInline($t))
            return new NoArgMapping($t->name);

        $c = $t->getConstructor();
        if ($c && $c->getNumberOfParameters() === 1)
            return new SingleScalarMapping($t->name, $var);

        return new MapperMapping($t->name, $var);
    }

    /**
     * Checks whether the type construction can be inline
     *
     * @param Type $t
     *
     * @return bool
     */
    public static function canBeInline(Type $t)
    {
        $c = $t->getConstructor();
        if ($c && $c->getNumberOfParameters())
            return false;

        foreach ($t->getMethods(Method::IS_PUBLIC) as $m) {
            if ($m->isAnnotatedWith(Bind::class))
                return false;
        }

        return true;
    }
} 
