<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::user() || !Auth::user()->hasAccess($permission)) {
            return redirect()->back()->with('toast-warn', 'У вас нет разрешения на выполнение этого действия. Для доступа необходимо получить права у зав. отделения.');
        }
        return $next($request);
    }
}
