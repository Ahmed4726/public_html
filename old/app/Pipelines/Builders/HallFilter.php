<?php

namespace App\Pipelines\Builders;

use Closure;

class HallFilter
{
    public function handle($request, Closure $next)
    {
        return ($hallID = request()->hall_id) ? $next($request->whereHallId($hallID)) : $next($request);
    }
}
