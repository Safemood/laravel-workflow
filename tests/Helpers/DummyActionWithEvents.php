<?php

namespace Tests\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Safemood\Workflow\Action;
use Safemood\Workflow\Contracts\DTOInterface;

class DummyActionWithEvents extends Action
{
    public function handle(DTOInterface &$context)
    {
        if (Arr::has($context->toArray(), 'throw_expection')) {
            throw new \Exception('A Dummy Exception');
        }

        Event::dispatch('App\Events\TestEvent', ['test' => 'data']);
        Event::dispatch('App\Events\AnotherEvent', ['another' => 'value']);
        Event::dispatch('OtherEvent', ['data' => 'data']);

        Log::info('testing', ['data' => 123]);

        $dummyModel = new DummyModel(['name' => 'Test Name', 'email' => 'test@example.com']);
        Event::dispatch('eloquent.created: '.DummyModel::class, $dummyModel);

        $context->handled = true;

    }
}
