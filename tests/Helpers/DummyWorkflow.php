<?php

namespace App\Workflows;

use Safemood\Workflow\Contracts\DTOInterface;
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

    public function handle(DTOInterface $context)
    {

        $this->addBeforeActions([
            new DummyAction,
            new DummyAction,
        ]);

        $this->addMainAction(new DummyActionWithEvents);

        $this->addAfterAction(new DummyJob);

        $this->when($this->trackEvents, fn () => $this->trackAllEvents());
        $this->when($this->registerObservers, function () {
            $this->registerObservers([
                DummyModel::class => DummyModelObserver::class,
            ]);
        });

    }
}
