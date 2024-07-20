<?php

namespace Safemood\Workflow\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeJob extends GeneratorCommand
{
    protected $name = 'workflow-job';

    protected $description = 'Create a new job class for workflow';

    protected $type = 'Job';

    protected function getStub(): string
    {
        return file_exists($customPath = $this->laravel->basePath('stubs/job.stub'))
            ? $customPath
            : __DIR__.'/../../stubs/job.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\\Jobs";
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the job class.'],
        ];
    }
}
