<?php

namespace Safemood\Workflow\Traits;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Events\JobFailed;
use Safemood\Workflow\Action;
use Safemood\Workflow\Enums\ActionState;

trait TracksActionStates
{
    /**
     * Initialize the state for the given action.
     *
     * @return void
     */
    public function initializeActionState(Action $action)
    {

        $action->setState(ActionState::PENDING);

    }

    /**
     * Update the state of the given action.
     *
     * @param  \Exception|null  $exception
     * @return void
     */
    public function updateActionState(Action $action, ActionState $state, $exception = null)
    {
        $action->setState($state);
        $action->setException($exception);
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
        return ! $this->passes();
    }
}
