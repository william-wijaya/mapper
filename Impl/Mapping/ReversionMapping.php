<?php
namespace Plum\Mapper\Impl\Mapping;

use Plum\Gen\CodeWriter;
use Plum\Mapper\Impl\Mapping;

class ReversionMapping implements Mapping
{
    private $getters;

    /**
     * @param GetterMapping[] $getters
     */
    public function __construct(array $getters)
    {
        $this->getters = $getters;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(CodeWriter $w)
    {
        foreach ($this->getters as $g)
            $g->compile($w);
    }
}
