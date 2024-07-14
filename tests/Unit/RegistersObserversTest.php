<?php

use Illuminate\Support\Facades\Event;
use Safemood\Workflow\Traits\RegistersObservers;
use Tests\Helpers\DummyModel;
use Tests\Helpers\DummyModelObserver;

beforeEach(function () {
    $this->dummyClass = new class
    {
        use RegistersObservers;

        public function callBootObserversIfNeeded()
        {
            $this->bootObserversIfNeeded();
        }
    };
});

it('can register a single observer', function () {
    $this->dummyClass->registerObserver('App\Models\User', 'App\Observers\UserObserver');

    expect($this->dummyClass->observers())->toBe(['App\Models\User' => 'App\Observers\UserObserver']);
});

it('can register multiple observers', function () {
    $observers = [
        'App\Models\User' => 'App\Observers\UserObserver',
        'App\Models\Post' => 'App\Observers\PostObserver',
    ];

    $this->dummyClass->registerObservers($observers);

    expect($this->dummyClass->observers())->toBe($observers);
});

it('does not overwrite existing observers', function () {
    $this->dummyClass->registerObserver('App\Models\User', 'App\Observers\UserObserver');
    $this->dummyClass->registerObserver('App\Models\User', 'App\Observers\AnotherUserObserver');

    expect($this->dummyClass->observers())->toBe(['App\Models\User' => 'App\Observers\UserObserver']);
});

it('boots observers', function () {
    Event::fake();

    $mockModel = DummyModel::class;
    $observer = DummyModelObserver::class;

    $this->dummyClass->registerObserver($mockModel, $observer);
    $this->dummyClass->callBootObserversIfNeeded();

    $attached = $this->assertObserverIsAttachedToEvent($observer, 'eloquent.booted: '.$mockModel);

    expect($attached)->toBeTrue();
});
