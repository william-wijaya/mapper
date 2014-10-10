<?php
namespace Plum\Mapper\Impl\Mapping;

use Plum\Gen\CodeWriter;
use Plum\Mapper\Impl\Mapping;

class BindMapping implements Mapping
{
    private $setters;

    /**
     * @param SetterMapping[] $setters
     */
    public function __construct(array $setters)
    {
        $this->setters = $setters;
    }

    /**
     * @return SetterMapping[]
     */
    public function setters()
    {
        return $this->setters;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $w)
    {
        foreach ($this->setters as $s)
            $s->compile($w);
    }
}
