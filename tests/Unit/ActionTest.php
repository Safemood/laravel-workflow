<?php

use Safemood\Workflow\Action;
use Safemood\Workflow\Contracts\DTOInterface;
use Safemood\Workflow\Enums\ActionState;
use Tests\Helpers\DummyDTO;

it('can set and get the state using ActionState', function () {
    $action = new class extends Action
    {
        public function handle(DTOInterface &$context) {}
    };

    $action->setState(ActionState::PENDING);

    expect($action->getState())->toBe(ActionState::PENDING);

    $action->setState(ActionState::SUCCESS);

    expect($action->getState())->toBe(ActionState::SUCCESS);
});

it('can set and get the exception', function () {
    $action = new class extends Action
    {
        public function handle(DTOInterface &$context) {}
    };

    $exception = new \Exception('Test Exception');
    $action->setException($exception);

    expect($action->getException())->toBe($exception);
});

it('should handle the abstract handle method', function () {
    $action = new class extends Action
    {
        public function handle(DTOInterface &$context)
        {
            // Example handle logic
            $context->handled = true;
        }
    };

    $context = new DummyDTO(
        ['id' => 1, 'name' => 'John Doe'],
        [
            ['id' => 1, 'name' => 'Product A', 'price' => 100, 'quantity' => 2],
            ['id' => 2, 'name' => 'Product B', 'price' => 50, 'quantity' => 1],
        ]
    );

    $action->handle($context);

    expect($context->handled)->toBeTrue();
});
