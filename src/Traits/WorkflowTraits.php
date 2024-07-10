<?php

namespace Safemood\Workflow\Traits;

trait WorkflowTraits
{
    use ActionsTrait;
    use ManagesWorkflowExecution;
    use RegistersObservers;
    use TracksActionStates;
    use TracksEvents;
    use HasResponses;
   

}
