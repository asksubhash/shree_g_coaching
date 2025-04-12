<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\Resource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect(url()->to('/'));
        }
        // dd(Auth::user()->role->role_name);
        if (!in_array(Auth::user()->role->role_code, $roles)) {
            return redirect(url()->to('/unauthorized-access'));
        }

        // Check for the routes is under maintenance or not
        $uri = request()->path();
        $resources = Resource::where([
            'record_status' => 1,
            'resource_link' => $uri
        ])->first();

        if (isset($resources->is_maintenance) && $resources->is_maintenance == 1) {
            return redirect(url()->to('/page-maintenance'));
        }

        return $next($request);
    }
}
