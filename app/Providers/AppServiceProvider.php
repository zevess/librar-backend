<?php

namespace App\Providers;

use App\Repositories\AuthorRepository;
use App\Repositories\AuthRepository;
use App\Repositories\BookRepository;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\AuthorService;
use App\Services\AuthService;
use App\Services\BookService;
use App\Services\Interfaces\AuthorServiceInterface;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\Interfaces\BookServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(BookServiceInterface::class, BookService::class);

        $this->app->bind(AuthorRepositoryInterface::class, AuthorRepository::class);
        $this->app->bind(AuthorServiceInterface::class, AuthorService::class);
    
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
