# FSMLib
## Description
PHP implementation of Automatas (Finite State Machines)

## Installation
```bash
composer require fsmlib/fsmlib
```

## Usage
```php
<?php

use Noctumsempra\Fsmlib\{ FSM, Transition,
                          State, InitialState, AcceptState, ErrorState };

require_once __DIR__ . '/vendor/autoload.php';

try {
  ob_start(); // Comienzo a capturar en un buffer la salida de la ejecución.

    $q0 = new InitialState('q0', true); // Defino el estado inicial.
    $qA = new AcceptState('qA');                // Defino el estado final.
    $qE = ErrorState::getInstance('qErr');      // Defino un estado de error singleton.

    // Este autómata solo APROBARÁ cadenas de 0 y 1 que no contengan el 1 repetido consecutivamente.
    $transitions = [ new Transition($q0, $q0, '0'), // Cada TRANSICIÓN es una REGLA.
                     new Transition($q0, $qA, '1'),
                     new Transition($qA, $q0, '0'),
                     new Transition($qA, $qE, '1')];

    $fsm = new FSM( $states   = [$q0, $qA, $qE],
                    $alphabet = ['0', '1'],
                    $q0, $transitions); // Instancio el autómata.

    // Defino los EVENTOS que se dispararán en cada etapa del autómata.
    $fsm->on('start',      fn(?State $state, $input)   => printf("FSM started in state: <b>%s</b> with input: <b>%s</b>.\n", $state->getName() ?? 'null', $input));
    $fsm->on('symbol',     fn(?State $state, $symbol)  => printf("  Reading symbol: <b>%s</b> in state: <b>%s</b>.\n", $symbol, $state->getName() ?? 'null'));
    $fsm->on('transition', fn(?Transition $transition) => printf("  Transitioned to state: <b>%s</b>.\n", $transition->getToState()->getName() ?? 'null'));
    $fsm->on('alphabet',   fn(array $alphabet)         => printf("FSM's alphabet has been set to: <b>%s</b>.\n", sprintf("[%s]", implode(', ', $alphabet))));
    $fsm->on('end',        fn(?State $state, $result)  => printf("FSM ended in state: <b>%s</b> with result: <b>%s</b>.\n", $state->getName() ?? 'null', $result ? 'ACCEPTED' : 'REJECTED'));

    $fsm->setAlphabet($alphabet); // Seteo el alfabeto de la FSM

    $run1 = $fsm->run('10100100101010'); // ACCEPTED
    $run2 = $fsm->run('10100100110100'); // REJECTED

  $output = ob_get_clean();

  printf("<pre>%s</pre>", $output);

} catch (Exception $e) {
  echo $e->getMessage();
}

```

## License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).
