<?php

namespace App\Workflows;

use Safemood\Workflow\WorkflowManager;
use Tests\Helpers\DummyAction;
use Tests\Helpers\DummyActionWithEvents;
use Tests\Helpers\DummyJob;
use Tests\Helpers\DummyModel;
use Tests\Helpers\DummyModelObserver;

class DummyWorkflow extends WorkflowManager
{
    public function __construct(
        protected $trackEvents = true,
        protected $registerObservers = true,
    ) {}

    public function handle(array $context)
    {

        $this->addBeforeActions([
            new DummyAction(),
            new DummyAction(),
        ]);

        $this->addMainAction(new DummyActionWithEvents());

        $this->addAfterAction(new DummyJob());

        if ($this->trackEvents) {
            $this->trackAllEvents();
        }

        if ($this->registerObservers) {
            $this->registerObservers([
                DummyModel::class => DummyModelObserver::class,
            ]);
        }

    }
}
