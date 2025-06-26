<?php

namespace App\Repositories;

use App\Exceptions\CacheException;
use App\Exceptions\InternalServerErrorException;
use Throwable;

class BaseRepository
{

    protected int $ttl = 3600 * 24 * 3;


    /**
     * @throws InternalServerErrorException
     */
    protected function safe(callable $callback)
    {
        try {
            return $callback();
        } catch (CacheException $e) {
            throw new InternalServerErrorException(
                message: 'Cache error: ' . $e->getMessage()
            );
        } catch (Throwable $e) {
            throw new InternalServerErrorException(
                message: 'Internal server error: ' . $e->getMessage()
            );
        }
    }
}
