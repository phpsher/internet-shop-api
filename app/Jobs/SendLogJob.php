<?php

namespace App\Jobs;

use App\Contracts\Services\LoggerServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Log;

class SendLogJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly mixed  $logData,
        private readonly string $logger,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!is_subclass_of($this->logger, LoggerServiceInterface::class)) {
            Log::warning("Invalid logger class: $this->logger");
            return;
        }

        $logger = app($this->logger);
        $logger->log($this->logData);
    }
}
