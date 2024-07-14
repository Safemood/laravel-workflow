<?php

namespace Tests\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Safemood\Workflow\Action;

class DummyActionWithEvents extends Action
{
    public function handle(array &$context)
    {
        if (Arr::has($context, 'throw_expection')) {
            throw new \Exception('A Dummy Exception');
        }

        Event::dispatch('App\Events\TestEvent', ['test' => 'data']);
        Event::dispatch('App\Events\AnotherEvent', ['another' => 'value']);
        Event::dispatch('OtherEvent', ['data' => 'data']);

        Log::info('testing', ['data' => 123]);

        $dummyModel = new DummyModel(['name' => 'Test Name', 'email' => 'test@example.com']);
        Event::dispatch('eloquent.created: '.DummyModel::class, $dummyModel);

        $context['handled'] = true;

    }
}
