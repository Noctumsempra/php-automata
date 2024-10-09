<?php

namespace Noctumsempra\Fsmlib;

class State {
  private string $name;
  private bool $isAcceptState;

  public function __construct(string $name, bool $isAccept = false) {
    $this->name = $name;
    $this->isAcceptState = $isAccept;
  }

  public function getName(): string {
    return $this->name;
  }

  public function isAcceptState(): bool {
    return $this->isAcceptState;
  }
}