<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request)
    {
        $route = match ($request->user()->role) {
            'admin' => 'admin.dashboard',
            'ceo' => 'ceo.dashboard',
            default => 'user.dashboard',
        };

        return redirect()->route($route);
    }
}
