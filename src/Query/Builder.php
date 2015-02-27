<?php namespace Packedge\Mongorm\Query;

/**
 * Class QueryBuilder
 * @package Packedge\Mongorm
 */
class Builder
{

    /**
     * Convert the given query to mongo syntax
     *
     * @param string $column
     * @param string|null $operator
     * @param string|null $value
     * @return array
     */
    public function parse($column, $operator = null, $value = null)
    {
        if (is_null($operator) && is_null($value)) {
            return [$column => ['$exists' => true]];
        }

        if (is_null($value)) {
            return [$column => $operator];
        }

        return $this->parseOperator($column, $operator, $value);
    }

    /**
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return array
     */
    protected function parseOperator($column, $operator, $value)
    {
        // TODO: don't use switch?
        switch ($operator) {
            case '=':
                return [$column => $value];
            case '!=':
            case '<>':
                return [$column => ['$ne' => $value]];
            case '>':
                return [$column => ['$gt' => $value]];
            case '>=':
                return [$column => ['$gte' => $value]];
            case '<':
                return [$column => ['$lt' => $value]];
            case '<=':
                return [$column => ['$lte' => $value]];
            default:
                throw new InvalidOperatorException;
        }
    }
}
