<?php

namespace AndrewSvirin\Examples;

/**
 * Demonstration of static:: and self:: methods.
 */
abstract class FooAbstract
{

    public static function foo()
    {
        return 'foo.foo';
    }

    public static function bar1()
    {
        return static::foo();
    }

    public static function bar2()
    {
        return self::foo();
    }

    public static function bar3()
    {
        return 'foo.bar3';
    }
}
