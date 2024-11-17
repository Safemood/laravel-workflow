<?php

use Tests\Helpers\DummyProductDTO;

beforeEach(function () {
    $this->productDTO = new DummyProductDTO('Laptop', 999.99, 5);
});

it('can create a DTO and access its properties', function () {
    expect($this->productDTO->getName())->toBe('Laptop');
    expect($this->productDTO->getPrice())->toBe(999.99);
    expect($this->productDTO->getQuantity())->toBe(5);
    expect($this->productDTO->getInStock())->toBeTrue();

    $this->productDTO->setQuantity(3);
    expect($this->productDTO->getQuantity())->toBe(3);

    $this->productDTO->setInStock(false);
    expect($this->productDTO->getInStock())->toBeFalse();
});

it('can convert the DTO to an array', function () {
    $productArray = $this->productDTO->toArray();

    expect($productArray)->toHaveKey('name');
    expect($productArray['name'])->toBe('Laptop');
    expect($productArray)->toHaveKey('price');
    expect($productArray['price'])->toBe(999.99);
    expect($productArray)->toHaveKey('quantity');
    expect($productArray['quantity'])->toBe(5);
    expect($productArray)->toHaveKey('inStock');
    expect($productArray['inStock'])->toBeTrue();
});
