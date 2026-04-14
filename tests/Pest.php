<?php

/*

|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------

|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may

| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

/*

|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------

|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "matchers" that help you make assertions.
|
*/

expect()->extend('toBeWithinRange', function (int $min, int $max) {
    return $this->toBeGreaterThanOrEqual($min)
                ->toBeLessThanOrEqual($max);
});
