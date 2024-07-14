<?php

namespace Safemood\Workflow\Traits;
 

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
