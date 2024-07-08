<?php

namespace Safemood\Workflow\Traits;

use Illuminate\Contracts\Queue\ShouldQueue;
use Safemood\Workflow\Action;

trait ActionsTrait
{
    protected $beforeActions = [];

    protected $mainActions = [];

    protected $afterActions = [];

    public function addBeforeAction(Action|ShouldQueue $action)
    {
        $this->beforeActions[] = $action;

        return $this;
    }

    public function addBeforeActions(array $actions)
    {
        foreach ($actions as $action) {
            $this->addBeforeAction($action);
        }

        return $this;
    }

    public function addMainAction(Action|ShouldQueue $action)
    {
        $this->mainActions[] = $action;

        return $this;
    }

    public function addMainActions(array $actions)
    {
        foreach ($actions as $action) {
            $this->addMainAction($action);
        }

        return $this;
    }

    public function addAfterAction(Action|ShouldQueue $action)
    {
        $this->afterActions[] = $action;

        return $this;
    }

    public function addAfterActions(array $actions)
    {
        foreach ($actions as $action) {
            $this->addAfterAction($action);
        }

        return $this;
    }

    public function getBeforeActions(): array
    {
        return $this->beforeActions;
    }

    public function getMainActions(): array
    {
        return $this->mainActions;
    }

    public function getAfterActions(): array
    {
        return $this->afterActions;
    }

    public function getActions(): array
    {
        return [
            'before' => $this->beforeActions,
            'main' => $this->mainActions,
            'after' => $this->afterActions,
        ];
    }
}
