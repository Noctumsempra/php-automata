<?php

namespace Noctumsempra\Fsmlib;

define('NEWLINE', (php_sapi_name()==='cli') ? "\n":"<br>");

class FSM {

  protected array $states;
  protected array $alphabet;
  protected array $transitions;
  protected ?State $initialState;
  protected ?State $currentState;
  protected array $events = [];

  /**
   * Create a new FSM
   *
   * @param State[] $states
   * @param array $alphabet
   * @param State|null $initialState
   * @param Transition[] $transitions
   */
  public function __construct(array $states = [], array $alphabet = [], ?State $initialState = null, array $transitions = []) {
    $this->states = $states;
    $this->alphabet = $alphabet;
    $this->initialState = $initialState ?? new NullState();
    $this->currentState = $initialState;
    $this->transitions = $transitions;
  }

  /**
   * Reset the FSM to the initial state
   */
  public function reset(): void {
    $this->trigger('reset', $this->currentState, $this->initialState);
    $this->currentState = $this->initialState;
  }

  /**
   * Run the FSM with the given input
   *
   * @param string $input
   * @param bool $reset
   * @return bool
   */

  public function run(string $input, bool $reset = true): bool {
    $reset && $this->reset();

    $this->trigger('start', $this->currentState, $input);

    foreach (str_split($input) as $symbol) {
      $this->trigger('symbol', $this->currentState, $symbol);

      if (!in_array($symbol, $this->alphabet)) {
        echo "\t\tSymbol $symbol not in alphabet\n";
        return false;
      }

      $this->currentState = $this->getNextState($this->currentState, $symbol, ErrorState::getInstance());

      if (!is_null($this->currentState) && !in_array($this->currentState, $this->states)) {
        echo "\t\tState " . $this->currentState->getName() . " not in states\n";
        return false;
      }
    }

    $result = ($this->currentState instanceof AcceptState) ||
              ($this->currentState->isAcceptState());

    $this->trigger('end', $this->currentState, $result);

    return $result;
  }

  /**
   * Get the next state given the current state and input symbol
   *
   * @param State|null $state
   * @param string $symbol
   * @param State|null $onErrorState
   * @return State|null
   */
  protected function getNextState(?State $state, string $symbol, ?State $onErrorState): ?State {
    foreach ($this->transitions as $transition) {
      if ($transition->getFromState()===$state && $transition->getSymbol()===$symbol) {
        printf("\tTransition found: <b>%s</b> ==<b>%s</b>=> <b>%s</b>\n", $state->getName(), $symbol, $transition->getToState()->getName());
        $this->trigger('transition', $transition);
        return $transition->getToState();
      }
    }

    return $onErrorState ?? new NullState();
  }

  /**
   * Set the alphabet
   *
   * @param string $string
   */
  public function setAlphabet(array $alphabet): void {
    $this->alphabet = $alphabet;
    $this->trigger('alphabet', $this->alphabet);
  }

  /**
   * Register an event callback
   *
   * @param string $event
   * @param callable $callback
   */
  public function on(string $event, callable $callback): void {
    if (!isset($this->events[$event])) {
      $this->events[$event] = [];
    }
    $this->events[$event][] = $callback;
  }

  /**
   * Trigger an event
   *
   * @param string $event
   * @param mixed ...$args
   */
  protected function trigger(string $event, ...$args): void {
    if (isset($this->events[$event])) {
      foreach ($this->events[$event] as $callback) {
        call_user_func($callback, ...$args);
      }
    }
  }
}