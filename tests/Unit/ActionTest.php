<?php

use Safemood\Workflow\Action;
use Safemood\Workflow\Enums\ActionState;

it('can set and get the state using ActionState', function () {
    $action = new class extends Action
    {
        public function handle(array &$context) {}
    };

    $action->setState(ActionState::PENDING);

    expect($action->getState())->toBe(ActionState::PENDING);

    $action->setState(ActionState::SUCCESS);

    expect($action->getState())->toBe(ActionState::SUCCESS);
});

it('can set and get the exception', function () {
    $action = new class extends Action
    {
        public function handle(array &$context) {}
    };

    $exception = new \Exception('Test Exception');
    $action->setException($exception);

    expect($action->getException())->toBe($exception);
});

it('should handle the abstract handle method', function () {
    $action = new class extends Action
    {
        public function handle(array &$context)
        {
            // Example handle logic
            $context['handled'] = true;
        }
    };

    $context = [];
    $action->handle($context);

    expect($context['handled'])->toBeTrue();
});
