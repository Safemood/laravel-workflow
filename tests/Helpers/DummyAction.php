<?php

namespace Tests\Helpers;

use Illuminate\Support\Arr;
use Safemood\Workflow\Action;

class DummyAction extends Action
{
    public function handle(array &$context)
    {
        if (Arr::has($context, 'throw_expection')) {
            throw new \Exception('A Dummy Exception');
        }

        $context['handled'] = true;

    }
}
