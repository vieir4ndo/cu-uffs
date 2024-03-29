<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanctum::ignoreMigrations();

        # Registering services
        $this->app->bind('App\Interfaces\Services\IAiPassportPhotoService', 'App\Services\AiPassportPhotoService');
        $this->app->bind('App\Interfaces\Services\IAuthService', 'App\Services\AuthService');
        $this->app->bind('App\Interfaces\Services\IBarcodeService', 'App\Services\BarcodeService');
        $this->app->bind('App\Interfaces\Services\ICaptchaMonsterService', 'App\Services\CaptchaMonsterService');
        $this->app->bind('App\Interfaces\Services\IEntryService', 'App\Services\EntryService');
        $this->app->bind('App\Interfaces\Services\IHttpClient', 'App\Services\HttpClient');
        $this->app->bind('App\Interfaces\Services\IIdUffsService', 'App\Services\IdUffsService');
        $this->app->bind('App\Interfaces\Services\IMailjetService', 'App\Services\MailjetService');
        $this->app->bind('App\Interfaces\Services\ITicketService', 'App\Services\TicketService');
        $this->app->bind('App\Interfaces\Services\IUserPayloadService', 'App\Services\UserPayloadService');
        $this->app->bind('App\Interfaces\Services\IUserService', 'App\Services\UserService');
        $this->app->bind('App\Interfaces\Services\IMenuService', 'App\Services\MenuService');
        $this->app->bind('App\Interfaces\Services\IBlockService', 'App\Services\BlockService');
        $this->app->bind('App\Interfaces\Services\IRoomService', 'App\Services\RoomService');
        $this->app->bind('App\Interfaces\Services\ICCRService', 'App\Services\CCRService');
        $this->app->bind('App\Interfaces\Services\IReserveService', 'App\Services\ReserveService');
        $this->app->bind('App\Interfaces\Services\IReportService', 'App\Services\ReportService');

        # Registering repositories
        $this->app->bind('App\Interfaces\Repositories\IEntryRepository', 'App\Repositories\EntryRepository');
        $this->app->bind('App\Interfaces\Repositories\ITicketRepository', 'App\Repositories\TicketRepository');
        $this->app->bind('App\Interfaces\Repositories\IUserPayloadRepository', 'App\Repositories\UserPayloadRepository');
        $this->app->bind('App\Interfaces\Repositories\IUserRepository', 'App\Repositories\UserRepository');
        $this->app->bind('App\Interfaces\Repositories\IMenuRepository', 'App\Repositories\MenuRepository');

        $this->app->bind('App\Interfaces\Repositories\IBlockRepository', 'App\Repositories\BlockRepository');
        $this->app->bind('App\Interfaces\Repositories\ICCRRepository', 'App\Repositories\CCRRepository');
        $this->app->bind('App\Interfaces\Repositories\IRoomRepository', 'App\Repositories\RoomRepository');
        $this->app->bind('App\Interfaces\Repositories\IReserveRepository', 'App\Repositories\ReserveRepository');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Fix wrong style/mix urls when being served from reverse proxy
        URL::forceRootUrl(config('app.url'));
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
