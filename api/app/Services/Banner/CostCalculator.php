<?php

declare(strict_types=1);

namespace App\Services\Banner;

class CostCalculator
{
    private $price;

    public function __construct(int $price)
    {
        $this->price = $price;
    }

    public function calc(int $views): int
    {
        return (int)floor($this->price * ($views / 1000));
    }
}
