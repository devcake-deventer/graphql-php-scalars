<?php


namespace Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\AssertionFailedError;

class TestUtil extends TestCase
{
    public static function assertThrows(callable $function, callable $exceptionCheck = NULL, $message = 'Expected to catch an exception')
    {
        try {
            $function();
            TestCase::fail($message);
        } catch (AssertionFailedError $afe) {
            throw $afe;
        } catch (Exception $e) {
            if ($exceptionCheck === NULL) {
                TestCase::assertTrue(true);
            } else {
                $exceptionCheck($e);
            }
        }
    }

}