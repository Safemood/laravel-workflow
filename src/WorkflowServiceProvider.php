<?php

namespace Safemood\Workflow;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Safemood\Workflow\Commands\WorkflowCommand;

class WorkflowServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {

        $package
            ->name('laravel-workflow')
            ->hasConfigFile()
            ->hasCommand(WorkflowCommand::class);
    }

}
