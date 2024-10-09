<?php

namespace Tests\Noctumsempra\Fsmlib;

use PHPUnit\Framework\TestCase;
use Noctumsempra\Fsmlib\State;
use Noctumsempra\Fsmlib\Transition;
use Noctumsempra\Fsmlib\FSM;

class FSMTest extends TestCase {

  public function testFSM() {
    $q0 = new State('q0');
    $q1 = new State('q1', true);

    $transitions = [
      new Transition($q0, $q1, 'a'),
      new Transition($q1, $q1, 'a'),
      new Transition($q1, $q0, 'b'),
    ];

    $fsm = new FSM([$q0, $q1], ['a', 'b'], $q0, $transitions);

    $this->assertTrue($fsm->run('a'));
    $this->assertFalse($fsm->run('b'));
    $this->assertTrue($fsm->run('aa'));
    $this->assertFalse($fsm->run('ab'));
  }
}
