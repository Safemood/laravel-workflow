<?php

namespace Safemood\Workflow;

abstract class Action
{
    private $state;
    private $exception;

    /**
     * Handle the action.
     *
     * @param array &$context The context data passed through the workflow.
     * @return void
     */
    abstract public function handle(array &$context);

    /**
     * Get the state of the action.
     *
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the state of the action.
     *
     * @param mixed $state
     * @return void
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get the exception of the action.
     *
     * @return \Exception|null
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Set the exception of the action.
     *
     * @param \Exception|null $exception
     * @return void
     */
    public function setException($exception)
    {
        $this->exception = $exception;
    }
}
