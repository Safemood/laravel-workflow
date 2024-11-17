<?php

use Illuminate\Support\Facades\Queue;
use Safemood\Workflow\Contracts\DTOInterface;
use Safemood\Workflow\Enums\ActionState;
use Safemood\Workflow\Traits\ActionsTrait;
use Safemood\Workflow\Traits\ManagesExecution;
use Safemood\Workflow\Traits\TracksActionStates;
use Tests\Helpers\DummyAction;
use Tests\Helpers\DummyDTO;
use Tests\Helpers\DummyJob;

beforeEach(function () {

    $this->workflow = new class
    {
        use ActionsTrait;
        use ManagesExecution;
        use TracksActionStates;

        public function executeAction($action, &$context)
        {
            $this->handleAction($action, $context);
        }

        public function isQueuedAction($action): bool
        {
            return $this->isDispatchable($action);
        }

        public function handle(DTOInterface &$context)
        {

            $context->handled = true;
        }

        public function triggerJob($action, DTOInterface &$context): void
        {
            $this->dispatchAction($action, $context);
        }

        public function processActions(array $actions, DTOInterface &$context): bool
        {
            return $this->executeActions($actions, $context);
        }

        public function processWorkflowActions(DTOInterface &$context)
        {
            return $this->executeWorkflowActions($context);
        }

        public function runWorkflow(DTOInterface &$context, bool $stopOnFailure = true, bool $autoBootObservers = true)
        {
            return $this->run($context, $stopOnFailure, $autoBootObservers);
        }
    };
});

it('sets and gets auto boot observers', function () {
    $this->workflow->setAutoBootObservers(false);
    expect($this->workflow->getAutoBootObservers())->toBeFalse();

    $this->workflow->setAutoBootObservers(true);
    expect($this->workflow->getAutoBootObservers())->toBeTrue();
});

it('sets and gets stop on failure', function () {
    $this->workflow->setStopOnFailure(false);
    expect($this->workflow->getStopOnFailure())->toBeFalse();

    $this->workflow->setStopOnFailure(true);
    expect($this->workflow->getStopOnFailure())->toBeTrue();
});

it('handles action correctly', function () {
    $context = new DummyDTO(
        ['id' => 1, 'name' => 'John Doe'],
        [
            ['id' => 1, 'name' => 'Product A', 'price' => 100, 'quantity' => 2],
            ['id' => 2, 'name' => 'Product B', 'price' => 50, 'quantity' => 1],
        ]
    );

    $this->workflow->executeAction(new DummyAction(), $context);

    expect($context)->toHaveKey('handled');
    expect($context->handled)->toBeTrue();
});

it('identifies job action correctly', function () {
    expect($this->workflow->isQueuedAction(new DummyAction()))->toBeFalse();
    expect($this->workflow->isQueuedAction(new DummyJob()))->toBeTrue();
});

it('dispatches job action', function () {

    Queue::fake();

    $context = new DummyDTO(
        ['id' => 1, 'name' => 'John Doe'],
        [
            ['id' => 1, 'name' => 'Product A', 'price' => 100, 'quantity' => 2],
            ['id' => 2, 'name' => 'Product B', 'price' => 50, 'quantity' => 1],
        ]
    );

    $dummyJob = new DummyJob();

    $this->workflow->triggerJob($dummyJob, $context);

    Queue::assertPushed(DummyJob::class);
});

it('executes actions successfully and updates their states', function () {

    $context = new DummyDTO(
        ['id' => 1, 'name' => 'John Doe'],
        [
            ['id' => 1, 'name' => 'Product A', 'price' => 100, 'quantity' => 2],
            ['id' => 2, 'name' => 'Product B', 'price' => 50, 'quantity' => 1],
        ]
    );

    $dummyAction = new DummyAction();
    $result = $this->workflow->processActions([$dummyAction], $context);

    expect($result)->toBeTrue();
    expect($dummyAction->getState())->toBe(ActionState::SUCCESS);
    expect($context->handled)->toBeTrue();
});

it('updates action state to FAILED if an exception is thrown', function () {

    $context = new DummyDTO([], [], ['throw_expection' => true]);

    $dummyAction = new DummyAction();
    $result = $this->workflow->processActions([$dummyAction], $context);

    expect($result)->toBeFalse();
    expect($dummyAction->getState())->toBe(ActionState::FAILED);
    expect($dummyAction->getException())->toBeInstanceOf(Exception::class);
    expect($dummyAction->getException()->getMessage())->toBe('A Dummy Exception');
});

it('processes workflow actions successfully', function () {
    $context = new DummyDTO(
        ['id' => 1, 'name' => 'John Doe'],
        [
            ['id' => 1, 'name' => 'Product A', 'price' => 100, 'quantity' => 2],
            ['id' => 2, 'name' => 'Product B', 'price' => 50, 'quantity' => 1],
        ]
    );
    $dummyAction = new DummyAction();
    $this->workflow->addBeforeAction($dummyAction);
    $this->workflow->addMainAction($dummyAction);
    $this->workflow->addAfterAction($dummyAction);

    $this->workflow->processWorkflowActions($context);

    expect($this->workflow->passes())->toBeTrue();
    expect($context)->toHaveKey('handled');
    expect($context->handled)->toBeTrue();
});

it('runs the workflow and initializes correctly', function () {
    $context = new DummyDTO(
        ['id' => 1, 'name' => 'John Doe'],
        [
            ['id' => 1, 'name' => 'Product A', 'price' => 100, 'quantity' => 2],
            ['id' => 2, 'name' => 'Product B', 'price' => 50, 'quantity' => 1],
        ]
    );

    $result = $this->workflow->runWorkflow($context);

    expect($result)->toBeInstanceOf(get_class($this->workflow));
    expect($result->getAutoBootObservers())->toBeTrue();
    expect($result->getStopOnFailure())->toBeTrue();
    expect($context)->toHaveKey('handled');
});

it('runs the workflow with stopOnFailure set to false', function () {
    $context = new DummyDTO(
        ['id' => 1, 'name' => 'John Doe'],
        [
            ['id' => 1, 'name' => 'Product A', 'price' => 100, 'quantity' => 2],
            ['id' => 2, 'name' => 'Product B', 'price' => 50, 'quantity' => 1],
        ]
    );

    $result = $this->workflow->runWorkflow($context, false);

    expect($result)->toBeInstanceOf(get_class($this->workflow));
    expect($result->getStopOnFailure())->toBeFalse();
    expect($context)->toHaveKey('handled');
});

it('runs the workflow with autoBootObservers set to false', function () {
    $context = new DummyDTO(
        ['id' => 1, 'name' => 'John Doe'],
        [
            ['id' => 1, 'name' => 'Product A', 'price' => 100, 'quantity' => 2],
            ['id' => 2, 'name' => 'Product B', 'price' => 50, 'quantity' => 1],
        ]
    );

    $result = $this->workflow->runWorkflow($context, true, false);

    expect($result)->toBeInstanceOf(get_class($this->workflow));
    expect($result->getAutoBootObservers())->toBeFalse();
    expect($context)->toHaveKey('handled');
});
