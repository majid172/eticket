<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Route;

class RouteController extends Controller
{
    /**
     * View all routes on the platform.
     */
    public function index()
    {
        $routes = Route::latest()->paginate(20);
        return response()->json($routes);
    }
}
