<?php

namespace Expresser\Database\Query\Grammars;

use Illuminate\Database\Query\Grammars\MySqlGrammar;

class WpdbGrammar extends MySqlGrammar
{
    public function parameter($value)
    {
        return $this->isExpression($value) ? $this->getValue($value) : $this->getPlaceholder($value);
    }

    protected function getPlaceholder($value)
    {
        $placeholder = '%s';

        if (is_int($value)) {
            $placeholder = '%d';
        } elseif (is_float($value)) {
            $placeholder = '%f';
        }

        return $placeholder;
    }
}
