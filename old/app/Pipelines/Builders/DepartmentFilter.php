<?php

namespace App\Pipelines\Builders;

use Closure;

class DepartmentFilter
{
    public function handle($request, Closure $next)
    {
        return ($departmentID = request()->department_id) ? $next($request->whereDepartmentId($departmentID)) : $next($request);
    }
}
