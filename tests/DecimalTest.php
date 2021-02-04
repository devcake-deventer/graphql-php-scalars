<?php


namespace Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use DEVcake\GraphQLScalars\PriceScalar;

class DecimalTest extends TestCase {
    private $scalar;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scalar = new PriceScalar();
    }

    public function testSerialize() {
        TestUtil::assertThrows(function () {
            $this->scalar->serialize('not an integer');
        }, function (Exception $e) {
            $this->assertStringContainsStringIgnoringCase("price", $e->getMessage());
            $this->assertStringContainsStringIgnoringCase("integer", $e->getMessage());
        });

        $this->assertEquals('1.00', $this->scalar->serialize(100));
        $this->assertEquals('1.50', $this->scalar->serialize(150));
        $this->assertEquals('-1.00', $this->scalar->serialize(-100));;
        $this->assertEquals('-1.50', $this->scalar->serialize(-150));
    }

    public function testParse() {
        TestUtil::assertThrows(function () {
            $this->scalar->parseValue(1337);
        }, function (Exception $e) {
            $this->assertStringContainsStringIgnoringCase("price", $e->getMessage());
            $this->assertStringContainsStringIgnoringCase("string", $e->getMessage());
        });

        TestUtil::assertThrows(function () {
            $this->scalar->parseValue('1.337');
        }, function (Exception $e) {
            $this->assertStringContainsStringIgnoringCase("price", $e->getMessage());
            $this->assertStringContainsStringIgnoringCase('2 decimal', $e->getMessage());
        });

        $this->assertEquals(100, $this->scalar->parseValue('1'));
        $this->assertEquals(100, $this->scalar->parseValue('1.00'));
        $this->assertEquals(150, $this->scalar->parseValue('1.50'));
        $this->assertEquals(-100, $this->scalar->parseValue('-1'));
        $this->assertEquals(-100, $this->scalar->parseValue('-1.00'));
        $this->assertEquals(-150, $this->scalar->parseValue('-1.50'));
    }

}