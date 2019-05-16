<?php

namespace Fingo\LaravelSessionFallback;

use Exception;
use Illuminate\Cache\RedisStore;
use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Log;

/**
 * Class SessionFallback
 * @package Fingo\LaravelSessionFallback
 */
class SessionFallback extends SessionManager
{
    /**
     * Create a new driver instance.
     *
     * @param  string $driver
     * @return mixed
     *
     * @throws \InvalidArgumentException
     * @throws Exception
     */
    protected function createDriver($driver)
    {
        try {
            return retry(
                config('session_fallback.attempts_before_fallback'),
                function () use ($driver) {
                    return parent::createDriver($driver);
                }, config('session_fallback.interval_between_attempts')
            );
        } catch (Exception $e) {
            Log::error($e);

            if ($newDriver = $this->nextDriver($driver)) {
                return $this->createDriver($newDriver);
            }
            // Throw the exception if we have exhaused all our options
            throw $e;
        }
    }

    /**
     * Create an instance of the Redis session driver.
     *
     * @return \Illuminate\Session\Store
     */
    protected function createRedisDriver()
    {
        $handler = $this->createCacheHandler('redis');

        $store = $handler->getCache()->getStore();

        if (method_exists($store, 'setConnection')) {
            $store->setConnection(
                $this->app['config']['session.connection']
            );
        }

        if (method_exists($store, 'getRedis')) {
            // Check if the connection is alive
            $store->getRedis()->ping();
        }

        return $this->buildSession($handler);
    }

    /**
     * Get next driver name based on fallback order
     *
     * @param $driverName
     * @return string|null
     */
    public function nextDriver($driverName)
    {
        $driverOrder = config('session_fallback.fallback_order');
        if (in_array($driverName, $driverOrder, true) && last($driverOrder) !== $driverName) {
            $nextKey = array_search($driverName, $driverOrder, true) + 1;
            return $driverOrder[$nextKey];
        }
        return null;
    }
}
