<?php
class O {
  /**
   * Force O functions to get loaded
   */
  static function init() {
    $classPath = realpath(dirname(__FILE__));
    // s()
    if (!class_exists("StringClass")) include($classPath."/StringClass.php");
    // a()
    if (!class_exists("ArrayClass")) include($classPath."/ArrayClass.php");
    // o()
    if (!class_exists("ObjectClass")) include($classPath."/ObjectClass.php");
    // c()
    if (!class_exists("ChainableClass")) include($classPath."/ChainableClass.php");
    // Validator and ReflectionClass
    if (!class_exists("Validator")) include($classPath."/Validator.php");
  }
}
