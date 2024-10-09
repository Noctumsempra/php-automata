<?php

namespace Noctumsempra\Fsmlib;

class NullState extends State {

  public function __construct(string $name = 'NullState') {
    parent::__construct($name, false);
  }

}