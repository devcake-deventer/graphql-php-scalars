<?php


namespace DEVcake\GraphQLScalars;

class PriceScalar extends DecimalScalar
{
    protected $type = "Price";
    protected $decimals = 2;
}