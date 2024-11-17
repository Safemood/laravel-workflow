<?php

namespace Tests\Helpers;

use Illuminate\Support\Arr;
use Safemood\Workflow\Action;
use Safemood\Workflow\Contracts\DTOInterface;

class DummyAction extends Action
{
    public function handle(DTOInterface &$context)
    {
        if (Arr::has($context->exception, 'throw_expection')) {
            throw new \Exception('A Dummy Exception');
        }

        $context->handled = true;

    }
}
