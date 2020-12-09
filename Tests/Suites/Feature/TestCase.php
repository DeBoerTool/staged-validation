<?php

namespace Dbt\StagedValidation\Tests\Suites\Feature;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Session\Middleware\StartSession;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /** @var int */
    public static $called = 0;

    /**
     * Perform test application bootstrapping.
     */
    protected function setUp (): void
    {
        parent::setUp();

        self::$called = 0;
    }

    /**
     * Define environment setup.
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp ($app)
    {
        $config = resolve(Repository::class);

        $config->set('app.key', 'base64:2+SetJaztC7g0a1sSF81LYsDasiWymO6tp8yVv6KGrA=');
        $config->set('database.default', 'mysql');

        $config->set('database.connections.mysql', [
            'driver' => env('DB_DRIVER'),
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'prefix' => env('DB_PASSWORD'),
        ]);

        resolve(Kernel::class)->pushMiddleware(StartSession::class);
    }
}
