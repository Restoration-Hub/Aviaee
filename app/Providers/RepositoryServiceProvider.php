<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Domain interfaces
use App\Domain\Interfaces\IUserRepository;
use App\Domain\Interfaces\IMissionRepository;

// Concrete repositories
use App\Repositories\UserRepository;
use App\Repositories\MissionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the interface to the concrete implementation
        $this->app->bind(
            IUserRepository::class,
            UserRepository::class
        );

        // Bind mission repository
        $this->app->bind(
            IMissionRepository::class,
            MissionRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
