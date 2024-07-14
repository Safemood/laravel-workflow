<?php

namespace Safemood\Workflow\Tests;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use ReflectionFunction;
use Safemood\Workflow\WorkflowServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Safemood\\Workflow\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            WorkflowServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-workflow_table.php.stub';
        $migration->up();
        */
    }

    public function assertObserverIsAttachedToEvent($expectedObserver, $event)
    {
        $dispatcher = app(Dispatcher::class);

        foreach ($dispatcher->getListeners(is_object($event) ? get_class($event) : $event) as $listenerClosure) {

            $closureVariables = (new ReflectionFunction($listenerClosure))->getStaticVariables();

            $listenerFunction = new ReflectionFunction($closureVariables['listener']);
            $listenerVariables = $listenerFunction->getStaticVariables();

            if (isset($listenerVariables['observer']) && $listenerVariables['observer'] === $expectedObserver) {
                return true;
            }
        }

        return false;
    }
}
