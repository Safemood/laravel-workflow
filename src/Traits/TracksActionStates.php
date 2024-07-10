<?php

namespace Safemood\Workflow\Traits;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Queue;
use Safemood\Workflow\Action;
use Safemood\Workflow\Enums\ActionState;

trait TracksActionStates
{

    /**
     * Initialize the state for the given action.
     *
     * @param mixed $action
     * @return void
     */
    public function initializeActionState($action)
    {

        if ($action instanceof Action) {
            $action->setState(ActionState::PENDING);
        }
    }

    /**
     * Update the state of the given action.
     *
     * @param mixed $action
     * @param string $state
     * @param \Exception|null $exception
     * @return void
     */
    public function updateActionState($action, $state, $exception = null)
    {

        if ($action instanceof Action) {
            $action->setState($state);
            $action->setException($exception);
        }
    }

    
    public function passes(): bool
    {
        $allActions = array_merge($this->beforeActions, $this->mainActions, $this->afterActions);

        foreach ($allActions as $action) {
            if (
                $action instanceof Action
                && $action->getState() !== ActionState::SUCCESS
            ) {
                return false;
            }

            if (
                $action instanceof ShouldQueue && $action instanceof JobFailed
            ) {
                return false;
            }

        }

        return true;
    }


    public function failed(): bool
    {
        return !$this->passes();
    }
}
