<?php

namespace App\Contracts\Repositories;

interface StatisticsRepositoryInterface
{
    public function getOrderStatistics(): array;
    public function getProductStatistics(): array;
}
