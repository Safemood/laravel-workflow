<?php

namespace Safemood\Workflow\Contracts;

interface WorkflowInterface
{
     public function handle(array $context);
}
