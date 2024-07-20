<?php

namespace Safemood\Workflow;

use Safemood\Workflow\Contracts\WorkflowInterface;
use Safemood\Workflow\Traits\WorkflowTraits;

abstract class WorkflowManager implements WorkflowInterface
{
    use WorkflowTraits;

    protected function when(bool $condition, callable $callback)
    {
        if ($condition) {
            $callback();
        }
    }
}
