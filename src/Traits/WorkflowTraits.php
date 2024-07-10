<?php

namespace Safemood\Workflow\Traits;

trait WorkflowTraits
{
    use ActionsTrait;
    use HasResponses;
    use ManagesWorkflowExecution;
    use RegistersObservers;
    use TracksActionStates;
    use TracksEvents;
}
