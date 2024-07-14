<?php

use Safemood\Workflow\Action;
use Safemood\Workflow\Traits\ActionsTrait;

beforeEach(function () {
    $this->dummyClass = new class
    {
        use ActionsTrait;
    };
});

it('can add and get before actions', function () {
    $beforeAction = Mockery::mock(Action::class);
    $this->dummyClass->addBeforeAction($beforeAction);

    expect($this->dummyClass->getBeforeActions())->toContain($beforeAction);
});

it('can add and get main actions', function () {
    $mainAction = Mockery::mock(Action::class);
    $this->dummyClass->addMainAction($mainAction);

    expect($this->dummyClass->getMainActions())->toContain($mainAction);
});

it('can add and get after actions', function () {
    $afterAction = Mockery::mock(Action::class);
    $this->dummyClass->addAfterAction($afterAction);

    expect($this->dummyClass->getAfterActions())->toContain($afterAction);
});

it('can add multiple before actions', function () {
    $beforeActions = [
        Mockery::mock(Action::class),
        Mockery::mock(Action::class),
    ];

    $this->dummyClass->addBeforeActions($beforeActions);

    expect($this->dummyClass->getBeforeActions())->toEqual($beforeActions);
});

it('can add multiple main actions', function () {
    $mainActions = [
        Mockery::mock(Action::class),
        Mockery::mock(Action::class),
    ];
    $this->dummyClass->addMainActions($mainActions);

    expect($this->dummyClass->getMainActions())->toEqual($mainActions);
});

it('can add multiple after actions', function () {
    $afterActions = [
        Mockery::mock(Action::class),
        Mockery::mock(Action::class),
    ];
    $this->dummyClass->addAfterActions($afterActions);

    expect($this->dummyClass->getAfterActions())->toEqual($afterActions);
});

it('can get all actions', function () {
    $beforeActions = [Mockery::mock(Action::class)];
    $mainActions = [Mockery::mock(Action::class)];
    $afterActions = [Mockery::mock(Action::class)];

    $this->dummyClass->addBeforeActions($beforeActions);
    $this->dummyClass->addMainActions($mainActions);
    $this->dummyClass->addAfterActions($afterActions);

    expect($this->dummyClass->getActions())->toEqual([
        'before' => $beforeActions,
        'main' => $mainActions,
        'after' => $afterActions,
    ]);
});
