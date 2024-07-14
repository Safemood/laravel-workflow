<?php

use Illuminate\Support\Facades\Event;
use Safemood\Workflow\Traits\TracksEvents;

beforeEach(function () {
    $this->dummyClass = new class
    {
        use TracksEvents;

        public function startTrackingAllEvents()
        {

            $this->trackAllEvents();
        }

        public function startTrackingEventsIn(string $namespace)
        {
            $this->trackEventsIn($namespace);
        }

        public function startTrackingEvents(array $events)
        {
            $this->trackEvents($events);
        }

        public function getTrackedEvents()
        {
            return $this->trackedEvents();
        }

        public function resetTrackedEvents()
        {
            $this->clearTrackedEvents();
        }
    };
});

it('tracks all events', function () {

    $this->dummyClass->startTrackingAllEvents();

    Event::dispatch('TestEvent', ['key' => 'value']);

    $trackedEvents = $this->dummyClass->getTrackedEvents();

    expect($trackedEvents)->toHaveCount(1);
    expect($trackedEvents[0]['event'])->toBe('TestEvent');
    expect($trackedEvents[0]['payload'])->toBe(['key' => 'value']);
});

it('tracks events within a specific namespace', function () {

    $this->dummyClass->startTrackingEventsIn('App\Events');

    Event::dispatch('App\Events\TestEvent', ['data' => 'test']);

    Event::dispatch('OtherEvent', ['data' => 'ignored']);

    $trackedEvents = $this->dummyClass->getTrackedEvents();
    expect($trackedEvents)->toHaveCount(1);
    expect($trackedEvents[0]['event'])->toBe('App\Events\TestEvent');
    expect($trackedEvents[0]['payload'])->toBe(['data' => 'test']);
});

it('tracks specified events', function () {

    $this->dummyClass->startTrackingEvents(['App\Events\TestEvent', 'App\Events\AnotherEvent']);

    Event::dispatch('App\Events\TestEvent', ['test' => 'data']);
    Event::dispatch('App\Events\AnotherEvent', ['another' => 'value']);
    Event::dispatch('App\Events\OtherEvent', ['data' => 'ignored']);

    $trackedEvents = $this->dummyClass->getTrackedEvents();
    expect($trackedEvents)->toHaveCount(2);
    expect($trackedEvents[0]['event'])->toBe('App\Events\TestEvent');
    expect($trackedEvents[1]['event'])->toBe('App\Events\AnotherEvent');
});

it('clears tracked events', function () {

    $this->dummyClass->startTrackingAllEvents();
    Event::dispatch('TestEvent', ['key' => 'value']);

    $trackedEvents = $this->dummyClass->getTrackedEvents();
    expect($trackedEvents)->toHaveCount(1);

    $this->dummyClass->resetTrackedEvents();

    expect($this->dummyClass->getTrackedEvents())->toBeEmpty();
});
