<?php

namespace Safemood\Workflow;

use Safemood\Workflow\Commands\WorkflowCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
