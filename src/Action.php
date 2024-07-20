<?php

namespace Safemood\Workflow;

use Safemood\Workflow\Enums\ActionState;

abstract class Action
{
    private $state;

    private $exception;

    /**
     * Handle the action.
     *
     * @param  array  &$context  The context data passed through the workflow.
     * @return void
     */
    abstract public function handle(array &$context);

    /**
     * Get the state of the action.
     *
     * @return ActionState|null
     */
    public function getState(): ?ActionState
    {
        return $this->state;
    }

    /**
     * Set the state of the action.
     *
     * @param  ActionState $state
     * @return void
     */
    public function setState(ActionState $state): void
    {
        $this->state = $state;
    }

    /**
     * Get the exception of the action.
     *
     * @return \Exception|null
     */
    public function getException(): ?\Exception
    {
        return $this->exception;
    }

    /**
     * Set the exception of the action.
     *
     * @param  \Exception|null  $exception
     * @return void
     */
    public function setException(?\Exception $exception): void
    {
        $this->exception = $exception;
    }
}
