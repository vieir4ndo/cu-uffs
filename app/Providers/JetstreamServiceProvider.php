<?php

namespace App\Providers;

use App\Enums\UserType;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Fortify;
use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();
        $this->configureLogin();

        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     *
     *
     * @return void
     */
    protected function configureLogin()
    {
        Fortify::authenticateUsing(function (Request $request) {

            $user = $this->userService->getUserByUsername($request->input('email'));

            if (empty($user)){
                return null;
            }

            echo UserType::RUEmployee;

            if (!Hash::check($user->password, $request->input('password'))){
                if ($user->type == UserType::RUEmployee or $user->type == UserType::ThirdPartyEmployee){
                    return $user;
                }
            }

            return null;
        });
    }

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
