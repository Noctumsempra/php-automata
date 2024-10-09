<?php

namespace Noctumsempra\Fsmlib;

class AcceptState extends State {

  public function __construct(string $name = 'Accept State') {
    parent::__construct($name, true);
  }
}