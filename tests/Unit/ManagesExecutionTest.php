<?php

use Safemood\Workflow\Enums\ActionState;
use Safemood\Workflow\Traits\ActionsTrait;
use Safemood\Workflow\Traits\ManagesExecution;
use Safemood\Workflow\Traits\TracksActionStates;
use Tests\Helpers\DummyAction;
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

        public function handle(array &$context)
        {

            $context['handled'] = true;
        }

        public function triggerJob($action, array &$context): void
        {
            $this->dispatchAction($action, $context);
        }

        public function processActions(array $actions, array &$context): bool
        {
            return $this->executeActions($actions, $context);
        }

        public function processWorkflowActions(array &$context)
        {
            return $this->executeWorkflowActions($context);
        }

        public function runWorkflow(array &$context, bool $stopOnFailure = true, bool $autoBootObservers = true)
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
    $context = [];

    $this->workflow->executeAction(new DummyAction(), $context);

    expect($context)->toHaveKey('handled');
    expect($context['handled'])->toBeTrue();
});

it('identifies job action correctly', function () {
    expect($this->workflow->isQueuedAction(new DummyAction()))->toBeFalse();
    expect($this->workflow->isQueuedAction(new DummyJob()))->toBeTrue();
});

it('dispatches job action', function () {

    Queue::fake();

    $context = ['key' => 'value'];

    $dummyJob = new DummyJob();

    $this->workflow->triggerJob($dummyJob, $context);

    Queue::assertPushed(DummyJob::class);
});

it('executes actions successfully and updates their states', function () {

    $context = [];
    $dummyAction = new DummyAction();
    $result = $this->workflow->processActions([$dummyAction], $context);

    expect($result)->toBeTrue();
    expect($dummyAction->getState())->toBe(ActionState::SUCCESS);
    expect($context['handled'])->toBeTrue();
});

it('updates action state to FAILED if an exception is thrown', function () {

    $context = ['throw_expection' => true];
    $dummyAction = new DummyAction();
    $result = $this->workflow->processActions([$dummyAction], $context);

    expect($result)->toBeFalse();
    expect($dummyAction->getState())->toBe(ActionState::FAILED);
    expect($dummyAction->getException())->toBeInstanceOf(Exception::class);
    expect($dummyAction->getException()->getMessage())->toBe('A Dummy Exception');
});

it('processes workflow actions successfully', function () {
    $context = [];
    $dummyAction = new DummyAction();
    $this->workflow->addBeforeAction($dummyAction);
    $this->workflow->addMainAction($dummyAction);
    $this->workflow->addAfterAction($dummyAction);

    $this->workflow->processWorkflowActions($context);

    expect($this->workflow->passes())->toBeTrue();
    expect($context)->toHaveKey('handled');
    expect($context['handled'])->toBeTrue();
});

it('runs the workflow and initializes correctly', function () {
    $context = [];

    $result = $this->workflow->runWorkflow($context);

    expect($result)->toBeInstanceOf(get_class($this->workflow));
    expect($result->getAutoBootObservers())->toBeTrue();
    expect($result->getStopOnFailure())->toBeTrue();
    expect($context)->toHaveKey('handled');
});

it('runs the workflow with stopOnFailure set to false', function () {
    $context = [];

    $result = $this->workflow->runWorkflow($context, false);

    expect($result)->toBeInstanceOf(get_class($this->workflow));
    expect($result->getStopOnFailure())->toBeFalse();
    expect($context)->toHaveKey('handled');
});

it('runs the workflow with autoBootObservers set to false', function () {
    $context = [];

    $result = $this->workflow->runWorkflow($context, true, false);

    expect($result)->toBeInstanceOf(get_class($this->workflow));
    expect($result->getAutoBootObservers())->toBeFalse();
    expect($context)->toHaveKey('handled');
});
