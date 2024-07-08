<?php

namespace Safemood\Workflow\Traits;

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
}
