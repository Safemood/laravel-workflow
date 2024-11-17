<?php

namespace Safemood\Workflow;

use Safemood\Workflow\Contracts\DTOInterface;
use Safemood\Workflow\Enums\ActionState;

abstract class Action
{
    private $state;

    private $exception;

    /**
     * Handle the action.
     *
     * @param  DTOInterface  $context  The context data passed through the workflow.
     * @return void
     */
    abstract public function handle(DTOInterface &$context);

    /**
     * Get the state of the action.
     */
    public function getState(): ?ActionState
    {
        return $this->state;
    }

    /**
     * Set the state of the action.
     */
    public function setState(ActionState $state): void
    {
        $this->state = $state;
    }

    /**
     * Get the exception of the action.
     */
    public function getException(): ?\Exception
    {
        return $this->exception;
    }

    /**
     * Set the exception of the action.
     */
    public function setException(?\Exception $exception): void
    {
        $this->exception = $exception;
    }
}
