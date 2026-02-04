<?php

namespace App\Providers;

use App\Repositories\AuthorRepository;
use App\Repositories\AuthRepository;
use App\Repositories\BookRepository;
use App\Repositories\GenreRepository;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\ReservationRepository;
use App\Repositories\UserRepository;
use App\Services\AuthorService;
use App\Services\AuthService;
use App\Services\BookService;
use App\Services\GenreService;
use App\Services\Interfaces\AuthorServiceInterface;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\Interfaces\BookServiceInterface;
use App\Services\Interfaces\GenreServiceInterface;
use App\Services\Interfaces\ReservationServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\ReservationService;
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

        $this->app->bind(ReservationServiceInterface::class, ReservationService::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);

        $this->app->bind(GenreRepositoryInterface::class, GenreRepository::class);
        $this->app->bind(GenreServiceInterface::class, GenreService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
