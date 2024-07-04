<?php

namespace Safemood\Workflow;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Safemood\Workflow\Commands\WorkflowCommand;

class WorkflowServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-workflow')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-workflow_table')
            ->hasCommand(WorkflowCommand::class);
    }
}
