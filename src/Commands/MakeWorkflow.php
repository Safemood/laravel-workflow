<?php

namespace Safemood\Workflow\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeWorkflow extends GeneratorCommand
{
    protected $name = 'make:workflow';
    protected $description = 'Create a new workflow class';

    protected $type = 'Workflow';

    protected function getStub(): string
    {
        return file_exists($customPath = $this->laravel->basePath('stubs/workflow.stub'))
            ? $customPath
            : __DIR__.'/../../stubs/workflow.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\\Workflows";
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the workflow class.'],
        ];
    }
}
