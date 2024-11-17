<?php

namespace Safemood\Workflow\Contracts;

interface WorkflowInterface
{
    /**
     * Handle the workflow with the given context.
     *
     *
     * @return mixed
     */
    public function handle(DTOInterface $context);
}
