<?php

namespace Safemood\Workflow\Traits;

use Illuminate\Support\Traits\Conditionable;

trait WorkflowTraits
{
    use ActionsTrait;
    use Dumpable;
    use HasResponses;
    use ManagesExecution;
    use RegistersObservers;
    use TracksActionStates;
    use TracksEvents;
    use Conditionable;
}
