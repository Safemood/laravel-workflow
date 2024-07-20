<?php

namespace Safemood\Workflow\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeAction extends GeneratorCommand
{
    protected $name = 'make:workflow-action';

    protected $description = 'Create a new action class';

    protected $type = 'Action';

    protected function getStub(): string
    {
        return file_exists($customPath = $this->laravel->basePath('stubs/action.stub'))
            ? $customPath
            : __DIR__.'/../../stubs/action.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\\Actions";
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the action class.'],
        ];
    }
}
