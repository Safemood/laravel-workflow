<?php

namespace Safemood\Workflow\Traits;
 
trait WorkflowTraits
{
    use ManagesWorkflowExecution;
    use ActionsTrait;
    use RegistersObservers;
    use TracksActionStates;
    use TracksEvents;
    use HasResponses;
   

}
