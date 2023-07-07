<?php

namespace App\Providers;

use App\Repositories\HistoriesRepositoryService\HistoriesRepository;
use App\Repositories\HistoriesRepositoryService\IHistoriesRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepositoryService\UserRepository;
use App\Repositories\WordRepositoryService\WordRepository;
use App\Repositories\UserRepositoryService\IUserRepository;
use App\Repositories\WordRepositoryService\IWordRepository;
use App\Repositories\MeansRepositoryService\MeansRepository;
use App\Repositories\MeansRepositoryService\IMeansRepository;
use App\Repositories\WordTypeRepositoryService\WordTypeRepository;
use App\Repositories\WordTypeRepositoryService\IWordTypeRepository;
use App\Repositories\SpecializationRepositoryService\SpecializationRepository;
use App\Repositories\SpecializationRepositoryService\ISpecializationRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IWordRepository::class, WordRepository::class);
        $this->app->bind(ISpecializationRepository::class, SpecializationRepository::class);
        $this->app->bind(IMeansRepository::class, MeansRepository::class);
        $this->app->bind(IWordTypeRepository::class, WordTypeRepository::class);
        $this->app->bind(IHistoriesRepository::class, HistoriesRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
