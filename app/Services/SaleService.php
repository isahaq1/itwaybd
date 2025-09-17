<?php

namespace App\Services;

use App\Models\Sale;
use App\Repositories\SaleRepository;

class SaleService
{
    public function __construct(private readonly SaleRepository $repository)
    {
    }

    public function createSale(array $saleData, array $itemsData): Sale
    {
        return $this->repository->create(saleData: $saleData, itemsData: $itemsData);
    }
}


