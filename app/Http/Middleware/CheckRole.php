<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // dd($role);
        if (! $request->user() || ! $request->user()->hasRole($role)) {
        // dd($role);
        return redirect()->back()->with([
                'message' => '申し訳ございません。許可のないページにアクセスしようとしています',
                'status' => 'danger']);
        }
        // dd($role);
        return $next($request);
    }
}
