<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function mockStorage()
    {
        Storage::extend('mock', function () {
            return \Mockery::mock(\Illuminate\Contracts\Filesystem\Filesystem::class);
        });

        Config::set('filesystems.disks.mock', ['driver' => 'mock']);
        Config::set('filesystems.default', 'mock');

        return Storage::disk(); 
    }
}
