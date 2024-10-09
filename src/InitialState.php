<?php

namespace Noctumsempra\Fsmlib;

class InitialState extends State {

  public function __construct(string $name = 'Initial State', bool $isAccept = false) {
    parent::__construct($name, $isAccept);
  }

}