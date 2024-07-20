<?php

namespace Safemood\Workflow\Enums;

enum ActionState: string
{
    case PENDING = 'pending';
    case RUNNING = 'running';
    case SUCCESS = 'success';
    case FAILED = 'failed';
}
