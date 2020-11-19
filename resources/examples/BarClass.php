<?php

namespace AndrewSvirin\Examples;

/**
 * Demonstration of static:: and self:: methods.
 */
 class BarClass extends FooAbstract
{

     /**
      * Override `foo`
      * @return string
      */
    public static function foo()
    {
        return 'bar.foo';
    }

     /**
      * Use `parent::` to point on method for parent class.
      * @return string|void
      */
    public static function bar3()
    {
        return parent::bar3();
    }
}
