<?php

use Safemood\Workflow\Traits\Dumpable;

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed()
    ->ignoring(Dumpable::class);
