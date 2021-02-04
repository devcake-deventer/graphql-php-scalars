<?php

namespace DEVcake\GraphQLScalars;

use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;

abstract class DecimalScalar extends ScalarType {
    protected $type = "Decimal";
    protected $decimals;
    private $scale;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->scale = 10 ** $this->decimals;
    }

    /**
     * Serializes an integer to a string.
     * @param int $value
     * @return string
     */
    public function serialize($value)
    {
        if (!is_int($value)) throw new Error("{$this->type} should be supplied as an integer amount");
        $sign = $value < 0 ? '-' : '';
        $value = abs($value);
        $whole = floor($value / $this->scale);
        $fraction = $value % $this->scale;
        return sprintf("%s%d.%0{$this->decimals}d", $sign, $whole, $fraction);
    }

    /**
     * Parse an external float-like into an integer
     * Formats like with and without decimals are accepted
     * Formats like with too many decimals are rejected
     * @param string $value
     * @return int
     */

    public function parseValue($value)
    {
        if (!is_string($value)) throw new Error("{$this->type} should be supplied as a string");
        if ($value[0] == '-') return -$this->parseValue(substr($value, 1));
        if (strpos($value, '.') !== FALSE) {
            [$euros, $cents] = explode('.', $value);
            if (strlen($cents) > $this->decimals) throw new Error("{$this->type} has more than {$this->decimals} decimal places");
            return intval($euros) * $this->scale + intval($cents);
        } else {
            return intval($value) * $this->scale;
        }
    }

    public function parseLiteral(Node $valueNode, array $variables = null)
    {
        if ($valueNode instanceof StringValueNode) {
            throw new Error("{$this->type} scalar can only parse strings, instead got a {$valueNode->kind}");
        }
        return $this->parseValue($valueNode->value);
    }
}