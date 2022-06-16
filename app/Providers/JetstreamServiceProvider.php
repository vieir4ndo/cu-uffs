<?php

namespace App\Providers;

use App\Enums\UserType;
use App\Http\Repositories\UserRepository;
use App\Http\Services\AuthService;
use App\Http\Services\BarcodeService;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Fortify;
use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use PHPUnit\Util\Exception;

class JetstreamServiceProvider extends ServiceProvider
{
    private UserService $userService;
    private User $user;
    private AuthService $authService;

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
        $this->authService = new AuthService($this->userService);
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
            $this->user = $this->userService->getUserByUsername($request->input('email'),false);
            $password = $request->input('password');

            if (empty($this->user)) {
                return null;
            }

            if ($this->user->type == UserType::default->value) {
                return null;
            } else if ($this->user->type == UserType::RUEmployee->value) {
                try {
                    $data = $this->authService->authWithIdUFFS($this->user->uid, $password);
                    $this->user->update($data);
                    return $this->user;
                } catch (Exception) {
                    return null;
                }
            } else {
                if (Hash::check($password, $this->user->password)) {
                    return $this->user;
                }
                return null;
            }
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
