<?php

namespace CustomPackages\CustomApp;

use CustomPackages\CustomApp\Commands\CrudGeneratorCommand;
use CustomPackages\CustomApp\Commands\GenerateLocaleTranslationsCommand;
use CustomPackages\CustomApp\Commands\GeneratePermissionTranslationsCommand;
use CustomPackages\CustomApp\Commands\InstallAdvancedLoggerCommand;
use CustomPackages\CustomApp\Commands\InstallCommand;
use CustomPackages\CustomApp\Commands\PublishTranslationsCommand;
use CustomPackages\CustomApp\Commands\ScanAndSaveTranslationsCommand;
use CustomPackages\CustomApp\Commands\SeedCraftableProUserCommand;
use CustomPackages\CustomApp\Commands\TestDbConnectionCommand;
use CustomPackages\CustomApp\Http\Controllers\Auth\AuthenticatedSessionController;
use CustomPackages\CustomApp\Http\Controllers\Auth\ConfirmablePasswordController;
use CustomPackages\CustomApp\Http\Controllers\Auth\EmailVerificationNotificationController;
use CustomPackages\CustomApp\Http\Controllers\Auth\EmailVerificationPromptController;
use CustomPackages\CustomApp\Http\Controllers\Auth\NewPasswordController;
use CustomPackages\CustomApp\Http\Controllers\Auth\PasswordResetLinkController;
use CustomPackages\CustomApp\Http\Controllers\Auth\RegisteredUserController;
use CustomPackages\CustomApp\Http\Controllers\Auth\VerifyEmailController;
use CustomPackages\CustomApp\Http\Controllers\CraftableProUser\CraftableProUserController;
use CustomPackages\CustomApp\Http\Controllers\CraftableProUser\CraftableProUserInvitationController;
use CustomPackages\CustomApp\Http\Controllers\CraftableProUser\MyPasswordController;
use CustomPackages\CustomApp\Http\Controllers\CraftableProUser\MyProfileController;
use CustomPackages\CustomApp\Http\Controllers\FileUploadController;
use CustomPackages\CustomApp\Http\Controllers\HomeController;
use CustomPackages\CustomApp\Http\Controllers\Media\MediaController;
use CustomPackages\CustomApp\Http\Controllers\Permissions\PermissionController;
use CustomPackages\CustomApp\Http\Controllers\Roles\RoleController;
use CustomPackages\CustomApp\Http\Controllers\Settings\SettingsController;
use CustomPackages\CustomApp\Http\Controllers\TagsController;
use CustomPackages\CustomApp\Http\Controllers\Translations\TranslationsController;
use CustomPackages\CustomApp\Http\Controllers\UnassignedMediaController;
use CustomPackages\CustomApp\Http\Middleware\Authenticate;
use CustomPackages\CustomApp\Http\Middleware\CraftableProHandleInertiaRequests;
use CustomPackages\CustomApp\Http\Middleware\EnsureEmailIsVerified;
use CustomPackages\CustomApp\Http\Middleware\RedirectIfAuthenticated;
use CustomPackages\CustomApp\Http\Middleware\SetLocale;
use CustomPackages\CustomApp\Http\Middleware\TrackLastActive;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CustomAppServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('custom-app')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigration('create_admin_user_password_resets_table')
            ->hasMigration('create_admin_users_table')
            ->hasMigration('define_roles_and_permissions')
            ->hasMigration('create_language_lines_table')
            ->hasMigration('create_tags_table')
            ->hasMigration('create_media_table')
            ->hasMigration('create_unassigned_media_table')
            ->hasMigration('create_general_settings')
            ->hasCommand(InstallCommand::class)
            ->hasCommand(ScanAndSaveTranslationsCommand::class)
            ->hasCommand(PublishTranslationsCommand::class)
            ->hasCommand(GeneratePermissionTranslationsCommand::class)
            ->hasCommand(GenerateLocaleTranslationsCommand::class)
            ->hasCommand(InstallAdvancedLoggerCommand::class)
            // TODO: just tmp for developing purposes
            ->hasCommand(SeedCraftableProUserCommand::class)
            ->hasCommand(CrudGeneratorCommand::class)
            ->hasCommand(TestDbConnectionCommand::class);

        $this->loadViewsFrom(__DIR__ . '/../resources/views/generator/', 'custom-packages/custom-app');
        $this->loadViewsFrom(__DIR__ . '/../resources/views/email_templates/', 'custom-packages/custom-app/email_templates');
    }

    public function bootingPackage()
    {
        Route::pushMiddlewareToGroup('custom-app-base-middlewares', SetLocale::class);
        Route::pushMiddlewareToGroup('custom-app-base-middlewares', TrackLastActive::class);
        Route::pushMiddlewareToGroup('custom-app-base-middlewares', config('custom-app.handle-inertia-request-class', CraftableProHandleInertiaRequests::class));

        Route::pushMiddlewareToGroup('custom-app-auth-middleware', Authenticate::class . ':custom-app');

        Route::pushMiddlewareToGroup('custom-app-guest-middleware', RedirectIfAuthenticated::class . ':custom-app');

        Route::pushMiddlewareToGroup('custom-app-verified-middleware', EnsureEmailIsVerified::class);

        Route::pushMiddlewareToGroup('custom-app-middlewares', SetLocale::class);
        Route::pushMiddlewareToGroup('custom-app-middlewares', TrackLastActive::class);
        Route::pushMiddlewareToGroup('custom-app-middlewares', Authenticate::class . ':custom-app');
        Route::pushMiddlewareToGroup('custom-app-middlewares', EnsureEmailIsVerified::class);
        Route::pushMiddlewareToGroup('custom-app-middlewares', config('custom-app.handle-inertia-request-class', CraftableProHandleInertiaRequests::class));
    }

    public function packageRegistered()
    {
        $this->mergeConfigFrom($this->package->basePath("/../config/auth.php"), 'auth');
        $this->mergeConfigFrom($this->package->basePath("/../config/filesystems.php"), 'filesystems');
        $this->mergeConfigFrom($this->package->basePath("/../config/translation-loader.php"), 'translation-loader');

        $this->publishes([
            $this->package->basePath('/../resources/js') => resource_path('js/custom-app'),
        ], "{$this->package->shortName()}-resources");

        $this->publishes([
            $this->package->basePath('/../database/seeders') => base_path('database/seeders'),
        ], "{$this->package->shortName()}-seeders");

        $this->publishes([
            $this->package->basePath('/Http/Middleware/CustomAppHandleInertiaRequests.php')
            => app_path('/Http/Middleware/CustomAppHandleInertiaRequests.php'),
        ], "custom-app-handle-inertia-requests");

        // TODO add prefix custom-app to the auth routes

        Route::macro('customApp', function (string $baseUrl = 'admin') {
            Route::name('custom-app.')->middleware('custom-app-base-middlewares')->prefix($baseUrl)->group(function () {
                Route::middleware('custom-app-guest-middleware')->group(function () {
                    if (config('custom-app.self_registration.enabled', false)) {
                        Route::get('register', [RegisteredUserController::class, 'create'])
                            ->name('register');

                        Route::post('register', [RegisteredUserController::class, 'store'])
                            ->name('register.store');
                    }

                    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                        ->name('login');

                    Route::post('login', [AuthenticatedSessionController::class, 'store']);

                    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                        ->name('password.request');

                    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                        ->middleware(['throttle:6,1'])
                        ->name('password.email');

                    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                        ->name('password.reset');

                    Route::post('reset-password', [NewPasswordController::class, 'store'])
                        ->name('password.update');

                    Route::get('invite-user/{email}', [CraftableProUserInvitationController::class, 'createInviteAcceptationUser'])->name('invite-user.create');
                    Route::post('invite-user', [CraftableProUserInvitationController::class, 'storeInviteAcceptationUser'])->name('invite-user.store');
                });

                Route::middleware('custom-app-auth-middleware')->group(function () {
                    // auth
                    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                        ->name('verification.notice');

                    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                        ->middleware(['signed', 'throttle:6,1'])
                        ->name('verification.verify');

                    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                        ->middleware('throttle:6,1')
                        ->name('verification.send');

                    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                        ->name('password.confirm');

                    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store'])
                        ->name('password.confirm.submit');

                    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                        ->name('logout');

                    Route::middleware('custom-app-verified-middleware')->group(function () {
                        // upload
                        Route::post('upload', [FileUploadController::class, 'upload'])->name('upload');
                        Route::post('unassigned-media-upload', [UnassignedMediaController::class, 'upload'])->name('unassignedMediaUpload');
                        Route::delete('unassigned-media-destroy/{id}', [UnassignedMediaController::class, 'destroy'])->name('unassignedMediaDestroy');

                        // home
                        Route::get('/', [HomeController::class, 'index'])
                            ->name('home');

                        // dashboard
                        Route::get('/dashboard', [HomeController::class, 'dashboard'])
                            ->name('dashboard');

                        // users crud
                        Route::delete('admin-users/bulk-destroy',  [CraftableProUserController::class, 'bulkDestroy']);
                        Route::resource('admin-users', CraftableProUserController::class)->parameters([
                            'admin-users' => 'adminUser',
                        ]);
                        Route::post('admin-users/{adminUser}/resend-verification-email',  [CraftableProUserController::class, 'resendEmailVerificationNotification']);
                        Route::post('admin-users/bulk-deactivate', [CraftableProUserController::class, 'bulkDeactivate']);
                        Route::post('admin-users/bulk-activate', [CraftableProUserController::class, 'bulkActivate']);
                        Route::get('admin-users/{adminUser}/impersonalLogin', [CraftableProUserController::class, 'impersonalLogin'])->name('admin-user.impersonalLogin');
                        Route::post('admin-users/invite-user', [CraftableProUserInvitationController::class, 'inviteUser'])->name('admin-user.invite-user');

                        // user profile

                        Route::get('profile', [MyProfileController::class, 'edit'])->name('admin-users.profile');
                        Route::put('profile', [MyProfileController::class, 'update'])->name('admin-users.profile.update');

                        Route::get('password', [MyPasswordController::class, 'edit'])->name('admin-users.password');
                        Route::put('password', [MyPasswordController::class, 'update'])->name('admin-users.password.update');

                        // translations management
                        Route::get('translations', [TranslationsController::class, 'index'])->name('translations.index');
                        Route::post('translations/rescan', [TranslationsController::class, 'rescan'])->name('translations.rescan');
                        Route::get('translations/export', [TranslationsController::class, 'export'])->name('translations.export');
                        Route::post('translations/import', [TranslationsController::class, 'import'])->name('translations.import');
                        Route::post('translations/import/conflicts', [TranslationsController::class, 'importResolvedConflicts'])->name('translations.import.conflicts');
                        Route::post('translations/publish', [TranslationsController::class, 'publish'])->name('translations.publish');
                        Route::post('translations/{translation}', [TranslationsController::class, 'update'])->name('translations.update');

                        // tags management
                        Route::post('tags', [TagsController::class, 'store'])->name('tags.store');

                        // media management
                        Route::get('media', [MediaController::class, 'index'])->name('media.index');
                        Route::get('media/images', [MediaController::class, 'images'])->name('media.images');
                        Route::get('media/files', [MediaController::class, 'files'])->name('media.files');
                        Route::post('media/update/{media}', [MediaController::class, 'updateMedia'])->name('media.update');

                        // permissions management
                        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
                        Route::put('permissions', [PermissionController::class, 'update'])->name('permissions.update');

                        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
                        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
                        Route::put('roles/{role}/update', [RoleController::class, 'update'])->name('roles.update');

                        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
                        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
                    });
                });
            });
        });
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        if (! ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            $config = $this->app->make('config');

            $config->set($key, $this->mergeConfig(
                require $path,
                $config->get($key, [])
            ));
        }
    }

    /**
     * Merges the configs together and takes multi-dimensional arrays into account.
     *
     * @param  array  $original
     * @param  array  $merging
     * @return array
     */
    protected function mergeConfig(array $original, array $merging)
    {
        $array = array_merge($original, $merging);

        foreach ($original as $key => $value) {
            if (! is_array($value)) {
                continue;
            }

            if (! Arr::exists($merging, $key)) {
                continue;
            }

            if (is_numeric($key)) {
                continue;
            }

            $array[$key] = $this->mergeConfig($value, $merging[$key]);
        }

        return $array;
    }
}
