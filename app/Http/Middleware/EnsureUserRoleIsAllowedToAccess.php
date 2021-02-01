<?php

namespace App\Http\Middleware;

use App\Models\UserPermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class EnsureUserRoleIsAllowedToAccess {
    // dashboard, pages, navigation-menus

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        try {
            $userRole = auth()->user()->role;
            $currentRouteName = Route::currentRouteName();
            #echo $userRole . '-' . $currentRouteName; exit;
            #$permission = UserPermission::isRoleHasRightToAccess($userRole, $currentRouteName);

            if (UserPermission::isRoleHasRightToAccess($userRole, $currentRouteName) || in_array($currentRouteName, $this->defaultUserAccessRole()[$userRole])) {
                return $next($request);
            } else {
                abort(403, 'Unauthorized action.');
            }
        } catch (\Throwable $th) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * The default user access role.
     *
     * @return void
     */
    private function defaultUserAccessRole() {
        return [
            'admin' => [
                'user-permissions',
                'dashboard',
                'users',
                'pages',
                'navigation-menus',
                'organizations'
            ],
            'manager' => [
                'dashboard',
                'users',
                'projects'
            ]
        ];
    }

}
