<?php

namespace App\Contracts\Services;

interface StatisticsServiceInterface
{
    public function getOrderStatistics();

    public function getProductStatistics();
}
