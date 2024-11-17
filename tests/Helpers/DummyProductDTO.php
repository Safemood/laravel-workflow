<?php

namespace Tests\Helpers;

use Safemood\Workflow\DTO\BaseDTO;

class DummyProductDTO extends BaseDTO
{
    public bool $inStock;

    public function __construct(
        public string $name,
        public float $price,
        public int $quantity,
    ) {

        $this->inStock = $this->quantity > 0;
    }
}
