<?php

use App\Workflows\DummyWorkflow;
use Illuminate\Support\Facades\Queue;
use Tests\Helpers\DummyDTO;
use Tests\Helpers\DummyJob;
use Tests\Helpers\DummyModel;
use Tests\Helpers\DummyModelObserver;

it('executes the DummyWorkflow correctly with events tracking and observers registrations', function () {

    $context = new DummyDTO(
        ['id' => 1, 'name' => 'John Doe'],
        [
            ['id' => 1, 'name' => 'Product A', 'price' => 100, 'quantity' => 2],
            ['id' => 2, 'name' => 'Product B', 'price' => 50, 'quantity' => 1],
        ]
    );

    Queue::fake();

    $workflow = (new DummyWorkflow)->run($context);

    expect($workflow->passes())->toBeTrue();

    Queue::assertPushed(DummyJob::class);

    $trackedEvents = $workflow->trackedEvents();

    expect($trackedEvents)->toHaveCount(8);
    expect($trackedEvents[0]['event'])->toBe('App\Events\TestEvent');
    expect($trackedEvents[1]['event'])->toBe('App\Events\AnotherEvent');
    expect($trackedEvents[2]['event'])->toBe('OtherEvent');
    expect($trackedEvents[3]['event'])->toBe('Illuminate\Log\Events\MessageLogged');
    expect($trackedEvents[4]['event'])->toBe('eloquent.booting: '.DummyModel::class);
    expect($trackedEvents[5]['event'])->toBe('eloquent.booted: '.DummyModel::class);
    expect($trackedEvents[6]['event'])->toBe('Illuminate\Log\Events\MessageLogged');
    expect($trackedEvents[7]['event'])->toBe('eloquent.created: '.DummyModel::class);

    $attached = $this->assertObserverIsAttachedToEvent(DummyModelObserver::class, 'eloquent.booted: '.DummyModel::class);

    expect($attached)->toBeTrue();
});

it('execute the DummyWorkflow correctly, failing to track events and register observers ', function () {

    $context = new DummyDTO(
        ['id' => 1, 'name' => 'John Doe'],
        [
            ['id' => 1, 'name' => 'Product A', 'price' => 100, 'quantity' => 2],
            ['id' => 2, 'name' => 'Product B', 'price' => 50, 'quantity' => 1],
        ]
    );

    Queue::fake();

    $workflow = (new DummyWorkflow(false, false))->run($context);

    expect($workflow->passes())->toBeTrue();

    Queue::assertPushed(DummyJob::class);

    $trackedEvents = $workflow->trackedEvents();

    expect($trackedEvents)->toBeEmpty();

    $attached = $this->assertObserverIsAttachedToEvent(
        DummyModelObserver::class, 'eloquent.booted: '.DummyModel::class
    );

    expect($attached)->toBeFalse();
});
