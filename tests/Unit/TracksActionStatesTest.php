<?php

use Safemood\Workflow\Action;
use Safemood\Workflow\Contracts\DTOInterface;
use Safemood\Workflow\Enums\ActionState;
use Safemood\Workflow\Traits\TracksActionStates;

beforeEach(function () {
    $this->dummyClass = new class
    {
        use TracksActionStates;

        public $beforeActions = [];

        public $mainActions = [];

        public $afterActions = [];

        // Methods to expose the protected trait methods for testing
        public function startTrackingActionState($action)
        {
            return $this->initializeActionState($action);
        }

        public function changeActionState($action, $state, $exception = null)
        {
            return $this->updateActionState($action, $state, $exception);
        }

        public function isPassing(): bool
        {
            return $this->passes();
        }

        public function isFailing(): bool
        {
            return $this->failed();
        }
    };

    $this->dummyAction = new class extends Action
    {
        protected $state;

        protected $exception;

        public function handle(DTOInterface &$context)
        {
            if (empty($context->cart)) {
                throw new \Exception('Cart is empty');
            }
            $context->toArray()['validated'] = true;
        }
    };
});

it('initializes action state', function () {
    $this->dummyClass->startTrackingActionState($this->dummyAction);
    expect($this->dummyAction->getState())->toBe(ActionState::PENDING);
});

it('updates action state', function () {
    $exception = new Exception('Test exception');
    $this->dummyClass->changeActionState($this->dummyAction, ActionState::FAILED, $exception);
    expect($this->dummyAction->getState())->toBe(ActionState::FAILED);
    expect($this->dummyAction->getException())->toBe($exception);
});

it('passes if all actions are successful', function () {
    $this->dummyAction->setState(ActionState::SUCCESS);

    $this->dummyClass->mainActions = [$this->dummyAction];
    expect($this->dummyClass->isPassing())->toBeTrue();
});

it('fails if any action is not successful', function () {

    $this->dummyAction->setState(ActionState::FAILED);

    $this->dummyClass->mainActions = [$this->dummyAction];

    expect($this->dummyClass->isPassing())->toBeFalse();
});
