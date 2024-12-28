<?php

namespace App\Http\Controllers\Laralum;

use App\TeacherDesignation;
use App\Http\Controllers\Controller;
use App\User;
use Hash;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $teacherByDesignation = TeacherDesignation::withCount(['teachers' => function ($query) {
            $query->whereIn('status', [1, 2, 4]);
        }])->get();
        $data['designations'] = $teacherByDesignation->pluck('name');
        $data['totalTeacher'] = $teacherByDesignation->pluck('teachers_count');

        return view('laralum/dashboard/index', $data);
    }

    public function bulkPasswordAssign()
    {
        $notLoggedInUser = User::whereNull('token')->get();
        foreach ($notLoggedInUser as $user) {
            $user->password = Hash::make('@' . strstr($user->email, '@', true) . '20#');
            $user->save();
        }
        return 'Successfully Completed';
    }
}
