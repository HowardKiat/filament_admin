<?php

namespace App\Providers;

use App\Filament\Resources\PermissionResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\UserResource;
use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use Filament\Navigation\MenuItem;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Contracts\Permission;
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
            try {
                $user = Auth::user();

                //Indepth Checking
                if ($user && 
                    $user->is_admin === 1 && 
                    $this->userHasAdminRoles($user)
                ) {
                    $this->registerAdminMenuItems();
                }
            } catch (\Exception $e) {
                // Log any unexpected errors
                Log::error('Error in Filament menu registration: ' . $e->getMessage());
            }
        });
    }

    /**
     * Check if the user has any admin-level roles
     * 
     * @param mixed $user
     * @return bool
     */
    protected function userHasAdminRoles($user): bool
    {
        if (!method_exists($user, 'hasAnyRole')) {
            return false;
        }

        return $user->hasAnyRole([
            'super-admin', 
            'admin', 
            'moderator'
        ]);
    }

    /**
     * Register admin-specific menu items
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