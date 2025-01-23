<?php

namespace App\Providers;

use App\Filament\Resources\PermissionResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\UserResource;
use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use Filament\Navigation\MenuItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            // Filament::registerViteTheme('resources/css/filament.css');

            try {
                $user = Auth::user();

                // Validate if the user exists and has admin privileges
                if ($user && $this->isAdmin($user)) {
                    $this->registerAdminMenuItems();
                }
            } catch (\Exception $e) {
                // Log unexpected errors
                Log::error('Error in Filament menu registration: ' . $e->getMessage());
            }
        });
    }

    /**
     * Check if the user has admin privileges.
     *
     * @param mixed $user
     * @return bool
     */
    protected function isAdmin($user): bool
    {
        if (!method_exists($user, 'hasAnyRole')) {
            return false;
        }

        return $user->is_admin === 1 && $user->hasAnyRole([
            'super-admin',
            'admin',
            'moderator',
        ]);
    }

    /**
     * Register admin-specific menu items in the Filament navigation.
     *
     * @return void
     */
    protected function registerAdminMenuItems(): void
    {
        Filament::registerUserMenuItems([
            MenuItem::make()
                ->label('Manage Users')
                ->url(UserResource::getUrl())
                ->icon('heroicon-s-users'),
            MenuItem::make()
                ->label('Manage Roles')
                ->url(RoleResource::getUrl())
                ->icon('heroicon-s-cog'),
            MenuItem::make()
                ->label('Manage Permissions')
                ->url(PermissionResource::getUrl())
                ->icon('heroicon-s-key'),
        ]);
    }
}
