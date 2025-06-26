<?php

namespace App\Services;

use App\Contracts\Repositories\StatisticsRepositoryInterface;
use App\Contracts\Services\StatisticsServiceInterface;

readonly class StatisticsService implements StatisticsServiceInterface
{
    public function __construct(
        private StatisticsRepositoryInterface $statisticsRepository
    )
    {
    }

    public function getOrderStatistics(): array
    {
        return $this->statisticsRepository->getOrderStatistics();
    }

    public function getProductStatistics(): array
    {
        return $this->statisticsRepository->getProductStatistics();
    }
}
