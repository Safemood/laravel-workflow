<?php

use Safemood\Workflow\Traits\HasResponses;

beforeEach(function () {
    $this->dummyClass = new class
    {
        use HasResponses;
    };
});

it('returns a success response', function () {

    $response = $this->dummyClass->successResponse();

    expect($response->status())->toBe(200);
    expect(json_decode($response->getContent(), true))->toBe([
        'status' => 'success',
        'message' => 'The operation completed successfully',
    ]);
});

it('returns a failure response', function () {

    $response = $this->dummyClass->failureResponse();

    expect($response->status())->toBe(400);
    expect(json_decode($response->getContent(), true))->toBe([
        'status' => 'failed',
        'message' => 'The operation failed to complete',
    ]);
});
