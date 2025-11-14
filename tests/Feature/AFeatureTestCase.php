<?php

declare(strict_types=1);

namespace Tests\Feature;

use Orchestra\Testbench\TestCase;

abstract class AFeatureTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            'Ldi\JobTracker\JobTrackerServiceProvider',
        ];
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    private function setUpDatabase(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}