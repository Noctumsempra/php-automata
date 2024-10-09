<?php

namespace Noctumsempra\Fsmlib;

class Transition {
  private State $fromState;
  private State $toState;
  private string $symbol;

  public function __construct(State $fromState, State $toState, mixed $symbol) {
    $this->fromState = $fromState;
    $this->toState = $toState;
    $this->symbol = (string) $symbol;
  }

  public function getFromState(): State {
    return $this->fromState;
  }

  public function getToState(): State {
    return $this->toState;
  }

  public function getSymbol(): string {
    return $this->symbol;
  }
}