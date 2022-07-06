<?php

namespace App\Providers;

use App\Services\AiPassportPhotoService;
use App\Services\AuthService;
use App\Services\BarcodeService;
use App\Services\IdUffsService;
use App\Services\MailjetService;
use App\Services\UserService;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Fortify;
use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    private UserService $userService;
    private $user;
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
        $mailJetService = new MailjetService();
        $aiPassportPhotoService = new AiPassportPhotoService();
        $idUffsService = new IdUffsService();
        $this->userService = new UserService($userRepository, $barcodeService, $aiPassportPhotoService, $idUffsService);
        $this->authService = new AuthService($this->userService, $mailJetService, $idUffsService);
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
            $this->user = $this->userService->getUserByUsernameFirstOrDefault($request->input('email'), false);
            $password = $request->input('password');

            if ($this->user == null) {
                return null;
            }

            if (in_array($this->user->type, config("user.users_allowed_login"))) {
                if (in_array($this->user->type, config("user.users_auth_iduffs"))) {
                    $data = $this->authService->authWithIdUFFS($this->user->uid, $password);
                    if ($data == null) {
                        return null;
                    }
                    $this->user->update($data);
                    return $this->user;
                } else {
                    if (Hash::check($password, $this->user->password)) {
                        return $this->user;
                    }
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
