<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function activeRouteClass($name)
    {
        $route = Route::currentRouteName();

        $arborescence = [
            'networks.index'            => ['networks.index', 'networks.show', 'networks.edit', 'networks.create'],
            'nurseries.index'           => ['nurseries.index', 'nurseries.show', 'nurseries.edit', 'nurseries.create', 'nurseries.planning'],
            'users.index'               => ['users.index', 'users.show', 'users.edit', 'users.create'],
            'bookings.index'            => ['booking-requests.index', 'bookings.index', 'bookings.show', 'bookings.edit', 'bookings.create'],
            'availabilities.search'     => ['availabilities.search'],
        ];

        foreach ($arborescence as $index => $item) {
            if (in_array($route, $item)) {
                return ($index == $name) ? 'active' : '';
            }
        }

        return '';
    }
}
