<?php

namespace Safemood\Workflow\Traits;

use Illuminate\Foundation\Bus\Dispatchable as DispatchableJob;
use Illuminate\Foundation\Events\Dispatchable as DispatchableEvent;
use Safemood\Workflow\Enums\ActionState;

trait ManagesExecution
{
    protected $autoBootObservers = true;

    protected $stopOnFailure = true;

    public function run(array &$context, bool $stopOnFailure = true, bool $autoBootObservers = true)
    {

        $this->setAutoBootObservers($autoBootObservers);
        $this->setStopOnFailure($stopOnFailure);
        $this->handle($context);
        $this->execute($context);

        return $this;
    }

    public function setAutoBootObservers(bool $value)
    {
        $this->autoBootObservers = $value;
    }

    public function setStopOnFailure(bool $value)
    {
        $this->stopOnFailure = $value;
    }

    public function getAutoBootObservers(): bool
    {
        return $this->autoBootObservers;
    }

    public function getStopOnFailure(): bool
    {
        return $this->stopOnFailure;
    }

    protected function execute(array $context)
    {
        try {
            if ($this->autoBootObservers && method_exists($this, 'bootObserversIfNeeded')) {
                $this->bootObserversIfNeeded();
            }

            $this->executeWorkflowActions($context);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function executeWorkflowActions(array &$context)
    {
        if (! $this->executeActions($this->beforeActions, $context)) {
            return;
        }
        if (! $this->executeActions($this->mainActions, $context)) {
            return;
        }

        if (! $this->executeActions($this->afterActions, $context)) {
            return;
        }
    }

    protected function executeActions(array $actions, array &$context)
    {
        foreach ($actions as $action) {
            $this->initializeActionState($action);

            try {
                if ($this->isDispatchable($action)) {
                    $this->dispatchAction($action, $context);
                } else {
                    $this->handleAction($action, $context);
                }

                $this->updateActionState($action, ActionState::SUCCESS);
            } catch (\Exception $e) {
                $this->updateActionState($action, ActionState::FAILED, $e);

                if ($this->stopOnFailure) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function handleAction($action, array &$context)
    {
        $action->handle($context);
    }

    protected function isDispatchable($action): bool
    {
        $traits = class_uses($action);

        return ! empty(array_intersect($traits, [
            DispatchableJob::class,
            DispatchableEvent::class,
        ]));

    }

    protected function dispatchAction($action, array &$context): void
    {

        $action::dispatch($context);

    }
}
