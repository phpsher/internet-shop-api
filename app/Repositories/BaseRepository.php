<?php

namespace App\Repositories;

use App\Exceptions\CacheException;
use App\Exceptions\InternalServerErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException(
                'Could not find a resource.',
            );
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
