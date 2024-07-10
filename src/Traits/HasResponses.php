<?php

namespace Safemood\Workflow\Traits;

trait HasResponses
{
    public function successResponse()
    {
        return response([
            'status' => 'success',
            'message' => 'The operation completed successfully'
        ], 200);
    }

    public function failureResponse()
    {
        return response([
            'status' => 'failed',
            'message' => 'The operation failed to complete'
        ], 400);
    }
}
