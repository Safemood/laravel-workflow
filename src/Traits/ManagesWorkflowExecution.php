<?php

namespace Safemood\Workflow\Traits;

use Safemood\Workflow\Enums\ActionState;
use Illuminate\Contracts\Queue\ShouldQueue;

trait ManagesWorkflowExecution
{
    protected $autoBootObservers = true;
    protected $stopOnFailure = true;

    public static function run(array $context, bool $autoBootObservers = true, bool $stopOnFailure = true)
    {
        $workflow = new static();
        $workflow->setAutoBootObservers($autoBootObservers);
        $workflow->setStopOnFailure($stopOnFailure);
        $workflow->handle($context);
        $workflow->execute($context);
        return $workflow;
    }

    public function setAutoBootObservers(bool $value)
    {
        $this->autoBootObservers = $value;
    }

    public function setStopOnFailure(bool $value)
    {
        $this->stopOnFailure = $value;
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
        if (!$this->executeActions($this->beforeActions, $context)) {
            return;
        }
        if (!$this->executeActions($this->mainActions, $context)) {
            return;
        }

        if (!$this->executeActions($this->afterActions, $context)) {
            return;
        }
    }

    protected function executeActions(array $actions, array &$context)
    {
        foreach ($actions as $action) {
            $this->initializeActionState($action);

            try {
                if ($this->isJobAction($action)) {
                    $this->dispatchJob($action, $context);
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

    protected function isJobAction($action): bool
    {
        return is_string($action) && is_subclass_of($action, ShouldQueue::class);
    }

    protected function dispatchJob($action, array $context): void
    {
        $job = $action::dispatch($context);
        $job->chain([
            function () use ($job, $action) {
                if ($job->jobFailed()) {
                    $this->updateActionState($action, ActionState::FAILED);
                }
            }
        ]);
    }
}
