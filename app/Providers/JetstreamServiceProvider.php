<?php

namespace App\Providers;

use App\Enums\UserType;
use App\Http\Repositories\UserRepository;
use App\Http\Services\BarcodeService;
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

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $userRepository = new UserRepository();
        $barcodeService = new BarcodeService();
        $this->userService = new UserService($userRepository, $barcodeService);
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

            // for running composer remove the value, we gotta check a workaround
            if (Hash::check(request('password'), $user->password)){
                if ((intval($user->type) == UserType::RUEmployee->value) || (intval($user->type) == UserType::ThirdPartyEmployee->value)){
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
