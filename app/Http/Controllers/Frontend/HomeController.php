<?php

namespace App\Http\Controllers\Frontend;

use App\AdministrativeMemberRole;
use App\Center;
use App\CenterFile;
use App\DepartmentFile;
use App\DiscussionTopic;
use App\Event;
use App\Faculty;
use App\GalleryImages;
use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['settings'] = Setting::first();
        $data['images'] = GalleryImages::allWithOptionalFilter(false, 101, 1, false, $data['settings']->banner_image_limit);
        $data['viceChancellor'] = AdministrativeMemberRole::with(['member', 'role'])->whereRoleId(69)->first();
        $data['firstEventSection'] = Event::listWithTopics($data['settings']->home_first_section_event);
        $data['secondEventSection'] = Event::listWithTopics($data['settings']->home_second_section_event);
        $data['thirdEventSection'] = Event::listWithTopics($data['settings']->home_third_section_event);
        $data['faculties'] = Faculty::with('departments')->whereType('FACULTY')->get();
        $data['instituteDepartment'] = Faculty::with('departments')->whereType('INSTITUTE')->first();
        $data['featured'] = DiscussionTopic::featured(false, 4);
        $data['spotlights'] = DiscussionTopic::spotlights($data['settings']->spotlight_number);

        return view('frontend.home', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadList()
    {
        $data['faculties'] = Faculty::with('departments')->get();
        $data['centers'] = Center::all();
        return view('frontend.download-list', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function fileSearch(Request $request)
    {
        if ($request->center_id) {
            return CenterFile::allWithOptionalFilter($request->search, 1, $request->center_id);
        } elseif ($request->department_id) {
            return DepartmentFile::allWithOptionalFilter($request->search, 1, $request->department_id);
        }
        // else
        //     return DepartmentFile::allWithOptionalFilter($request->search, 1);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notFound()
    {
        return view('frontend.not-found');
    }

    public function somethingWrong()
    {
        return view('frontend.something-wrong');
    }
}
