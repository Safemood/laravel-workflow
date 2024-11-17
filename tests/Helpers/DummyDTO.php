<?php

namespace Tests\Helpers;

use Safemood\Workflow\DTO\BaseDTO;

class DummyDTO extends BaseDTO
{
    public bool $handled = false;

    public function __construct(
        public array $user,
        public array $cart,
        public array $exception = [],
    ) {}
}
