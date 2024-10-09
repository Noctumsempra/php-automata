<?php

namespace Noctumsempra\Fsmlib;

class ErrorState extends State {
  private static ?ErrorState $instance = null;

  private function __construct(string $name) {
    parent::__construct($name, false);
  }

  public static function getInstance($name = 'Error State'): ErrorState {
    if (self::$instance === null) {
      self::$instance = new self($name);
    }
    return self::$instance;
  }
}