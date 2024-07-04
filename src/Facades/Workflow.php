<?php

namespace Safemood\Workflow\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Safemood\Workflow\Workflow
 */
class Workflow extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Safemood\Workflow\Workflow::class;
    }
}
