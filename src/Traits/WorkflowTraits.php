<?php

namespace Safemood\Workflow\Traits;
use Illuminate\Support\Traits\Dumpable;

trait WorkflowTraits
{
    use ActionsTrait;
    use HasResponses;
    use ManagesExecution;
    use RegistersObservers;
    use TracksActionStates;
    use TracksEvents;
    use Dumpable;
}
