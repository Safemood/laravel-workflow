<?php

namespace Safemood\Workflow\Traits;

use Safemood\Workflow\Enums\ActionState;

trait HandlesCallbacks
{
    protected $stateCallback;
    protected $successCallback;
    protected $failedCallback;

    public function progress(callable $callback)
    {
        $this->stateCallback = $callback;
        return $this;
    }

    public function success(callable $callback)
    {
        $this->successCallback = $callback;
        return $this;
    }

    public function failed(callable $callback)
    {
        $this->failedCallback = $callback;
        return $this;
    }

    protected function triggerCallbacks()
    {
        if ($this->stateCallback) {
            call_user_func($this->stateCallback, $this->getActionStates());
        }

        $successActions = array_filter($this->getActionStates(), function ($state) {
            return $state['state'] === ActionState::SUCCESS;
        });

        if ($this->successCallback) {
            call_user_func($this->successCallback, $successActions);
        }

        $failedActions = array_filter($this->getActionStates(), function ($state) {
            return $state['state'] === ActionState::FAILED;
        });

        if ($this->failedCallback) {
            call_user_func($this->failedCallback, $failedActions);
        }
    }
}
