<?php

namespace Safemood\Workflow;

use Safemood\Workflow\Commands\WorkflowCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
