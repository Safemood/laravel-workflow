<?php

namespace Safemood\Workflow;


use Safemood\Workflow\Commands\MakeAction;
use Safemood\Workflow\Commands\MakeWorkflow;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WorkflowServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {

        $package
            ->name('laravel-workflow')
            ->hasCommands([
                MakeWorkflow::class,
                MakeAction::class
            ]);
    }

}
