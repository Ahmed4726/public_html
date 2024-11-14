<?php

namespace App\Http\Controllers\Laralum;

use App\Center;
use App\Department;
use App\DiscussionTopic;
use App\DiscussionTopicFile;
use App\Event;
use App\Faculty;
use App\GalleryImageCategories;
use App\GalleryImages;
use App\Officer;
use App\Role;
use App\Setting;
use App\Teacher;
use App\User;
use App\UserEvent;
use App\UserRoles;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Settings;
use Illuminate\Support\Facades\Auth;
use Laralum;
use Storage;
use Mail;

class SettingsController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAllBannerImage()
    {
        $data['imageList'] = GalleryImages::imageByCategory('BANNER');
        return view('laralum.image.banner', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function galleryCategoryList()
    {
        $this->authorize('ADMIN');
        $data['faculties'] = Faculty::all();
        $data['departments'] = Department::all();
        $data['categories'] = GalleryImageCategories::all();
        return view('laralum.image.category_list', $data);
    }

    /**
     * @param GalleryImageCategories $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function galleryCategoryEdit(GalleryImageCategories $id)
    {
        $this->authorize('ADMIN');
        $data['faculties'] = Faculty::all();
        $data['departments'] = Department::get();
        $data['category'] = $id;
        return view('laralum.image.category_edit', $data);
    }

    /**
     * @param GalleryImageCategories $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function galleryCategoryUpdate(GalleryImageCategories $id, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required'
        ]);

        $id->name = $request->name;
        $id->help_text = $request->help_text;
        $id->description = $request->description;
        $id->department_id = isset($request->department_id) ? $request->department_id : null;

        if ($request->width) $id->width = $request->width;
        if ($request->height) $id->height = $request->height;
        if ($request->max_image_size_in_kb) $id->max_image_size_in_kb = $request->max_image_size_in_kb;

        $id->save();

        return redirect()->route('Laralum::gallery::category::list')->with('success', 'Gallery Image Type Updated Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function galleryCategoryCreate()
    {
        $this->authorize('ADMIN');
        $data['departments'] = Department::get(['id', 'short_name']);
        return view('laralum.image.category_create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function galleryCategoryStore(Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $galleryCategory = new GalleryImageCategories();
        $galleryCategory->name = $request->name;
        $galleryCategory->help_text = $request->help_text;
        $galleryCategory->description = $request->description;

        if ($request->width) $galleryCategory->width = $request->width;
        if ($request->height) $galleryCategory->height = $request->height;
        if ($request->max_image_size_in_kb) $galleryCategory->max_image_size_in_kb = $request->max_image_size_in_kb;
        if (isset($request->department_id)) $galleryCategory->department_id = $request->department_id;

        $galleryCategory->save();

        return redirect()->route('Laralum::gallery::category::list')->with('success', 'Gallery Image Type Created Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function allImageList(Request $request)
    {
        $this->authorize('ADMIN');
        $data['categories'] = GalleryImageCategories::whereNull('department_id')->get();
        $data['images'] = GalleryImages::allWithOptionalFilter($request->search, $request->category_id, $request->status, false,  false, 'galleryImageCategories')->appends($request->all());
        $data['sortable'] = false;
        $filterRequest = array_filter($request->except('page'));
        if(count($filterRequest) == 1 && array_key_exists('category_id', $filterRequest))
            $data['sortable'] = true;
        return view('laralum.image.index', $data);
    }

    /**
     * @param $department
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function departmentImageList($department, Request $request)
    {
        $data['uriValue'] = $department;
        if(request()->is('admin/department/*')){
            $this->authorize('DEPARTMENT-SPECIFIC', $department);
            $data['uri'] = 'department';
            $data['images'] = GalleryImages::allWithOptionalFilter($request->search, $request->category_id, $request->status, $department,  false, 'galleryImageCategories')->appends($request->all());
        }
        $data['categories'] = GalleryImageCategories::whereDepartmentId($department)->get();
        $data['sortable'] = false;
        $filterRequest = array_filter($request->except('page'));

        if(count($filterRequest) == 1 && array_key_exists('category_id', $filterRequest))
            $data['sortable'] = true;

        return view('laralum.image.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function jsonImageList(Request $request)
    {
        return GalleryImages::allWithOptionalFilter($request->search, $request->category_id, $request->status, $request->department_id,  false, 'galleryImageCategories');
    }

    /**
     * @param GalleryImageCategories $categories
     * @param GalleryImages $image
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function imageDelete(GalleryImageCategories $categories, GalleryImages $image)
    {
        if($categories->department_id)
            $this->authorize('DEPARTMENT-SPECIFIC', $categories->department_id);
        else
            $this->authorize('ADMIN');

        $image->delete();
        if($categories->department_id)
            return redirect()->route('Laralum::department::gallery::image::list', ['department' => $categories->department_id])->with('success', 'Image Deleted Successfully');
        else
            return redirect()->route('Laralum::gallery::image::list')->with('success', 'Image Deleted Successfully');
    }

    /**
     * @param GalleryImages $image
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function imageEdit(GalleryImages $image)
    {
        if($image->galleryImageCategories()->exists() && $image->galleryImageCategories->department_id){
            $this->authorize('DEPARTMENT-SPECIFIC', $image->galleryImageCategories->department_id);
            $data['uriValue'] = $image->galleryImageCategories->department_id;
            $data['uri'] = 'department';
            $data['categories'] = GalleryImageCategories::whereDepartmentId($image->galleryImageCategories->department_id)->get();
        }else{
            $this->authorize('ADMIN');
            $data['categories'] = GalleryImageCategories::whereNull('department_id')->get();
        }

        $data['image'] = $image;
        return view('laralum.image.edit', $data);
    }

    /**
     * @param GalleryImages $image
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function imageUpdate(GalleryImages $image, Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required',
        ]);

        ($image->path == $request->path) ? $imageUploadRequired = false :  $imageUploadRequired = true;

        $image->title = $request->title;
        $image->external_link = $request->external_link;
        $image->description = $request->description;
        $image->enabled = (isset($request->enabled) == 'on' ? '1' : '0');
        $image->category_id = $request->category_id;
        $galleryCategory = GalleryImageCategories::find($request->category_id);

        if ($galleryCategory->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $galleryCategory->department_id);
            $uri = 'department';
            $redirectParam['department'] = $galleryCategory->department_id;
        }

        if($request->hasFile('image') && $imageUploadRequired){
            $maxSize = $galleryCategory->max_image_size_in_kb;
            $height = $galleryCategory->height;
            $width = $galleryCategory->width;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/gallery/$fileName", $width, $height);
            Storage::delete("public/image/gallery/$image->path");
            $image->path = $fileName;
        }

        $image->save();
        if(isset($uri))
            return redirect()->route("Laralum::$uri::gallery::image::list", $redirectParam)->with('success', 'Image Updated Successfully');
        else
            return redirect()->route('Laralum::gallery::image::list', ['category_id' => $request->category_id])->with('success', 'Image Updated Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function storeImage(Request $request)
    {
        $galleryCategory = GalleryImageCategories::find($request->category_id);

        if ($galleryCategory->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $galleryCategory->department_id);
            $uri = 'department';
            $redirectParam['department'] = $galleryCategory->department_id;
        }

        $maxSize = $galleryCategory->max_image_size_in_kb;
        $height = $galleryCategory->height;
        $width = $galleryCategory->width;

        $this->validate($request, [
            'image' => "required|image|max:$maxSize",
            'category_id' => 'required'
        ]);

        $image = new GalleryImages();
        $image->title = $request->title;
        $image->external_link = $request->external_link;
        $image->description = $request->description;
        $image->enabled = (isset($request->enabled) == 'on' ? '1' : '0');
        $image->category_id = $request->category_id;

        $fileName = $request->image->hashName();
        Laralum::imageResizeWithUpload($request->image, "public/image/gallery/$fileName", $width, $height);
        $image->path = $fileName;
        $image->save();

        if(isset($uri))
            return redirect()->route("Laralum::$uri::gallery::image::list", $redirectParam)->with('success', 'Image Created Successfully');
        else
            return redirect()->route('Laralum::gallery::image::list', ['category_id' => $request->category_id])->with('success', 'Image Created Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function eventTypeList()
    {
        $this->authorize('ADMIN');
        $data['events'] = Event::latest()->get();
        return view('laralum.event.type-list', $data);
    }

    /**
     * @param Event $event
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function eventTypeEdit(Event $event)
    {
        $this->authorize('ADMIN');
        $data['event'] = $event;
        return view('laralum.event.type-edit', $data);
    }

    /**
     * @param Event $event
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function eventTypeUpdate(Event $event, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
           'name' => 'required'
        ]);

        $event->name = $request->name;
        if ($request->max_size) $event->max_size = $request->max_size;
        if ($request->width) $event->width = $request->width;
        if ($request->height) $event->height = $request->height;
        $event->save();
        return redirect()->route('Laralum::event::type::list')->with('success', 'Event Updated Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function eventTypeCreate()
    {
        $this->authorize('ADMIN');
        return view('laralum.event.type-create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function eventTypeStore(Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
           'name' => 'required'
        ]);

        $event = new Event();
        $event->name = $request->name;
        if ($request->max_size) $event->max_size = $request->max_size;
        if ($request->width) $event->width = $request->width;
        if ($request->height) $event->height = $request->height;
        $event->save();
        return redirect()->route('Laralum::event::type::list')->with('success', 'All Notice Type Created Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function eventList(Request $request)
    {
        if (!Auth::user()->can('ADMIN') && Auth::user()->can('EVENT')){
            $this->authorize('EVENT');
            $userEvent = UserEvent::whereUserId(Auth::user()->id)->get(['event_id'])->pluck(['event_id']);
            $data['events'] = Event::whereIn('id', $userEvent)->get();
            $data['discussions'] = DiscussionTopic::allWithOptionalFilter($request->search, false, $request->event_id, $request->status, $request->from_date, $request->to_date, false, ['event', 'department'], false, $userEvent)->appends($request->all());
        }else{
            $this->authorize('ADMIN');
            $data['events'] = Event::all();
            $data['discussions'] = DiscussionTopic::allWithOptionalFilter($request->search, false, $request->event_id, $request->status, $request->from_date, $request->to_date, false, ['event', 'department'])->appends($request->all());
        }

        return view('laralum.event.index', $data);
    }

    /**
     * @param $department
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function departmentEvent($department, Request $request)
    {
        $data['uriValue'] = $department;
        if(request()->is('admin/department/*')){
            $this->authorize('DEPARTMENT-SPECIFIC', $department);
            $data['discussions'] = DiscussionTopic::allWithOptionalFilter($request->search, $department, $request->event_id, $request->status, $request->from_date, $request->to_date, false, ['event', 'department'])->appends($request->all());
            $data['uri'] = 'department';
        }
        $data['events'] = Event::whereIn('id', Setting::getDataByKey('department_event'))->get();
        return view('laralum.event.index', $data);
    }

    /**
     * @param DiscussionTopic $discussion
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function eventEdit(DiscussionTopic $discussion)
    {
        if($discussion->department_id){
            $this->authorize('DEPARTMENT-SPECIFIC', $discussion->department_id);
            $data['uriValue'] = $discussion->department_id;
            $data['uri'] = 'department';
            $data['events'] = Event::whereIn('id', Setting::getDataByKey('department_event'))->get();
        }
        elseif (!Auth::user()->can('ADMIN') && Auth::user()->can('EVENT')){
            $this->authorize('EVENT-SPECIFIC', $discussion->event_id);
            $userEvent = UserEvent::whereUserId(Auth::user()->id)->get(['event_id'])->pluck(['event_id']);
            $data['events'] = Event::whereIn('id', $userEvent)->get();
        }
        else{
            $this->authorize('ADMIN');
            $data['events'] = Event::all();
        }

        $data['discussion'] = $discussion;
        return view('laralum.event.edit', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function eventCreate()
    {
        if (!Auth::user()->can('ADMIN') && Auth::user()->can('EVENT')){
            $this->authorize('EVENT');
            $userEvent = UserEvent::whereUserId(Auth::user()->id)->get(['event_id'])->pluck(['event_id']);
            $data['events'] = Event::whereIn('id', $userEvent)->get();
        } else{
            $this->authorize('ADMIN');
            $data['events'] = Event::all();
        }

        return view('laralum.event.create', $data);
    }

    /**
     * @param $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function departmentEventCreate($department)
    {
        $data['uriValue'] = $department;
        if(request()->is('admin/department/*')){
            $this->authorize('DEPARTMENT-SPECIFIC', $department);
            $data['uri'] = 'department';
        }

        $data['events'] = Event::whereIn('id', Setting::getDataByKey('department_event'))->get();
        return view('laralum.event.create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function eventStore(Request $request)
    {
        $this->validate($request, [
           'title' => 'required',
           'event_id' => 'required',
        ]);


        $discussion = new DiscussionTopic();
        $event = Event::find($request->event_id, ['max_size', 'height', 'width', 'name']);

        if (isset($request->department_id)) {
            $this->authorize('DEPARTMENT-SPECIFIC', $request->department_id);
            $discussion->department_id = $request->department_id;
            $uri = 'department';
            $redirectParam['department'] = $request->department_id;
        } else
            $this->authorize('EVENT-SPECIFIC', $request->event_id);

        if($request->hasFile('image')){
            $maxSize = $event->max_size;
            $height = $event->height;
            $width = $event->width;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/discussion/$fileName", $width, $height);
            $discussion->image_url = $fileName;
        }

        $discussion->title = $request->title;
        $discussion->event_id = $request->event_id;
        $discussion->type = $event->name;
        $discussion->details = $request->details;
        $discussion->external_link = $request->external_link;
        $discussion->reporter = $request->reporter;

        $discussion->enabled = (isset($request->enabled)) ? 1 : 0;
        $discussion->featured_news = (isset($request->featured_news)) ? 1 : 0;
        $discussion->highlight = (isset($request->highlight)) ? 1 : 0;
        $discussion->spotlight = (isset($request->spotlight)) ? 1 : 0;
        $discussion->show_publish_date = (isset($request->show_publish_date)) ? 1 : 0;
        $discussion->publish_date = ($request->publish_date) ? date('Y-m-d H:i:s', strtotime($request->publish_date)) : date('Y-m-d H:i:s');
        $discussion->save();

        if(isset($uri))
            return redirect()->route("Laralum::$uri::event::list", $redirectParam)->with('success', 'All Notice Created Successfully');
        else
            return redirect()->route('Laralum::event::list', ['event_id' => $request->event_id])->with('success', 'All Notice Created Successfully');
    }

    /**
     * @param DiscussionTopic $discussion
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function eventUpdate(DiscussionTopic $discussion, Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'event_id' => 'required',
        ]);

        ($discussion->image_url == $request->path) ? $imageUploadRequired = false :  $imageUploadRequired = true;
        $event = Event::find($request->event_id, ['max_size', 'height', 'width', 'name']);

        if ($discussion->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $discussion->department_id);
            $uri = 'department';
            $redirectParam['department'] = $discussion->department_id;
        }
        else
            $this->authorize('EVENT-SPECIFIC', $request->event_id);

        if($request->hasFile('image') && $imageUploadRequired){
            $maxSize = $event->max_size;
            $height = $event->height;
            $width = $event->width;
            $this->validate($request, [
                'image' => "required|image|max:$maxSize",
            ]);

            $fileName = $request->image->hashName();
            Laralum::imageResizeWithUpload($request->image, "public/image/discussion/$fileName", $width, $height);
            if($discussion->image_url) Storage::delete("public/image/discussion/$discussion->image_url");
            $discussion->image_url = $fileName;
        }

        $discussion->title = $request->title;
        $discussion->event_id = $request->event_id;
        $discussion->type = $event->name;
        $discussion->details = $request->details;
        $discussion->external_link = $request->external_link;
        $discussion->reporter = $request->reporter;

        $discussion->enabled = (isset($request->enabled)) ? 1 : 0;
        $discussion->featured_news = (isset($request->featured_news)) ? 1 : 0;
        $discussion->highlight = (isset($request->highlight)) ? 1 : 0;
        $discussion->spotlight = (isset($request->spotlight)) ? 1 : 0;
        $discussion->show_publish_date = (isset($request->show_publish_date)) ? 1 : 0;

        $discussion->publish_date = ($request->publish_date) ? date('Y-m-d H:i:s', strtotime($request->publish_date)) : date('Y-m-d H:i:s');
        $discussion->save();

        if(isset($uri))
            return redirect()->route("Laralum::$uri::event::list", $redirectParam)->with('success', 'All Notice Updated Successfully');
        else
            return redirect()->route('Laralum::event::list', ['event_id' => $request->event_id])->with('success', 'All Notice Updated Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createImage()
    {
        $this->authorize('ADMIN');
        $data['categories'] = GalleryImageCategories::whereNull('department_id')->get();
        return view('laralum.image.create', $data);
    }

    /**
     * @param $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function departmentImageCreate($department)
    {
        $data['uriValue'] = $department;
        if(request()->is('admin/department/*')){
            $this->authorize('DEPARTMENT-SPECIFIC', $department);
            $data['uri'] = 'department';
        }
        $data['categories'] = GalleryImageCategories::whereDepartmentId($department)->get();
        return view('laralum.image.create', $data);
    }

    /**
     * @param DiscussionTopic $discussion
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function upload(DiscussionTopic $discussion)
    {
        if($discussion->department_id)
            $this->authorize('DEPARTMENT-SPECIFIC', $discussion->department_id);
        elseif (!Auth::user()->can('ADMIN') && Auth::user()->can('EVENT'))
            $this->authorize('EVENT-SPECIFIC', $discussion->event_id);
        else
            $this->authorize('ADMIN');

        $data['files'] = DiscussionTopicFile::whereDiscussionTopicId($discussion->id)->get();
        $data['discussion'] = $discussion;
        return view('laralum.event.upload', $data);
    }

    /**
     * @param DiscussionTopic $discussion
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function eventDelete(DiscussionTopic $discussion)
    {
        if($discussion->department_id)
            $this->authorize('DEPARTMENT-SPECIFIC', $discussion->department_id);
        elseif (!Auth::user()->can('ADMIN') && Auth::user()->can('EVENT'))
            $this->authorize('EVENT-SPECIFIC', $discussion->event_id);
        else
            $this->authorize('ADMIN');

        $discussion->delete();
        if($discussion->department_id)
            return redirect()->route('Laralum::department::event::list', ['department' => $discussion->department_id])->with('success', 'All Notice Deleted Successfully ');
        else
            return redirect()->route('Laralum::event::list')->with('success', 'All Notice Deleted Successfully ');
    }

    /**
     * @param DiscussionTopic $discussion
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadSave(DiscussionTopic $discussion, Request $request)
    {
        if($discussion->department_id)
            $this->authorize('DEPARTMENT-SPECIFIC', $discussion->department_id);
        elseif (!Auth::user()->can('ADMIN') && Auth::user()->can('EVENT'))
            $this->authorize('EVENT-SPECIFIC', $discussion->event_id);
        else
            $this->authorize('ADMIN');

        $this->validate($request, [
            'name' => 'required',
            'file' => 'required'
        ]);

        $eventFile = new DiscussionTopicFile();
        $eventFile->discussion_topic_id = $discussion->id;
        $eventFile->name = $request->name;
        $eventFile->note = $request->note;
        if($request->hasFile('file')) {
            $request->file->store("public/image/discussion/$discussion->id");
            $eventFile->path = $request->file->hashName();
        }
        $eventFile->save();
        return redirect()->route('Laralum::event::upload', ['discussion' => $discussion->id])->with('success', 'File Uploaded Successfully');
    }
    
    public function editOld()
    {
        Laralum::permissionToAccess('laralum.settings.access');

        # Check permissions
        $row = Settings::first();

        $data_index = 'settings';
        require('Data/Edit/Get.php');

        return view('laralum/settings/general', [
            'row'       =>  $row,
            'fields'    =>  $fields,
            'confirmed' =>  $confirmed,
            'empty'     =>  $empty,
            'encrypted' =>  $encrypted,
            'hashed'    =>  $hashed,
            'masked'    =>  $masked,
            'table'     =>  $table,
            'code'      =>  $code,
            'wysiwyg'   =>  $wysiwyg,
            'relations' =>  $relations,
        ]);
    }

    public function edit()
    {
        $data['events'] = Event::all();
        $data['setting'] = Setting::first();
        return view('laralum.settings.edit', $data);
    }

    public function updateOld(Request $request)
    {
        Laralum::permissionToAccess('laralum.settings.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.settings.edit');

        $row = Settings::first();

        $data_index = 'settings';
        require('Data/Edit/Save.php');

        return redirect('admin/settings')->with('success', "The settings have been updated");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'type' => 'required',
//            'default_password_new_user' => 'required',
            'frontend_pagination_number' => 'required',
            'backend_pagination_number' => 'required'
        ]);

        $setting = Setting::first();
        $setting->type = $request->type;
        $setting->email = $request->email;
        $setting->phone = $request->phone;
        $setting->fax = $request->fax;
        $setting->top_contact_menu = $request->top_contact_menu;
        $setting->welcome_message = $request->welcome_message;
        $setting->address = $request->address;
        $setting->footer_text = $request->footer_text;
        $setting->copyright_text = $request->copyright_text;
        $setting->owner_msg = $request->owner_msg;
        $setting->contact_us_link = $request->contact_us_link;
        $setting->jobs_link = $request->jobs_link;
        $setting->webmail_link = $request->webmail_link;
        $setting->about_us_link = $request->about_us_link;
        $setting->mission_and_vission_link = $request->mission_and_vission_link;
        $setting->facebook_link = $request->facebook_link;
        $setting->twitter_link = $request->twitter_link;
        $setting->linkedin_link = $request->linkedin_link;
        $setting->featured_news_enabled = (isset($request->featured_news_enabled)) ? 1 : 0;
        $setting->hall_enabled = (isset($request->hall_enabled)) ? 1 : 0;
        $setting->animate_header_admission_link = (isset($request->animate_header_admission_link)) ? 1 : 0;
        $setting->max_profile_image_size_in_kb = $request->max_profile_image_size_in_kb;
        $setting->max_discussion_image_size_in_kb = $request->max_discussion_image_size_in_kb;
        $setting->banner_image_limit = $request->banner_image_limit;
        $setting->custom_css = $request->custom_css;
        $setting->custom_js = $request->custom_js;
        $setting->home_first_section_event = explode(',', $request->home_first_section_event);
        $setting->home_second_section_event = explode(',', $request->home_second_section_event);
        $setting->home_third_section_event = explode(',', $request->home_third_section_event);
        $setting->department_event = explode(',', $request->department_event);
//        $setting->default_password_new_user = $request->default_password_new_user;
        $setting->frontend_pagination_number = $request->frontend_pagination_number;
        $setting->backend_pagination_number = $request->backend_pagination_number;
        $setting->spotlight_number = $request->spotlight_number;
        $setting->save();
        return redirect()->route('Laralum::setting::edit')->with('success', 'Settings Updated Successfully');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function emailBroadcast()
    {
        $this->authorize('ADMIN');
        $data['centers'] = Center::all();
        $data['departments'] = Department::all();
        $data['roles'] = Role::all();
        return view('laralum.settings.email-broadcast', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function emailBroadcastSend(Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'subject' => 'required',
            'body' => 'required'
        ]);
        $emailAddress = array_filter(array_map('trim', explode(',', $request->email)));

        $userRolesEmails = array();
        if($request->role_id){
            $userRoles = UserRoles::whereRoleId($request->role_id)->pluck('user_id');
            $userRolesEmails = User::find($userRoles, ['email'])->pluck('email')->all();
        }
        $totalEmails = array_unique (array_merge ($emailAddress, $userRolesEmails));

        $departmentTeacherEmails = array();
        if($request->department_id)
            $departmentTeacherEmails = Teacher::whereDepartmentId($request->department_id)->whereStatus(1)->pluck('email')->all();

        $totalEmails = array_unique (array_merge ($totalEmails, $departmentTeacherEmails));

        $centerOfficerEmails = array();
        if($request->center_id)
            $centerOfficerEmails = Officer::whereCenterId($request->center_id)->whereStatus(1)->whereTypeId(1)->pluck('email')->all();

        $totalEmails = array_unique (array_merge ($totalEmails, $centerOfficerEmails));

        if(empty($totalEmails))
            return redirect()->route('Laralum::email::broadcast')->with('error', 'No Recipient Email Found');

        Mail::send('laralum.settings.email', ['email' => $request], function ($message) use ($request, $totalEmails) {
            $message->subject($request->subject)
                ->to($totalEmails);
        });

        return redirect()->route('Laralum::email::broadcast')->with('success', 'Email sent successfully');
    }

    /**
     * @param $event
     * @param DiscussionTopicFile $file
     * @return mixed
     */
    public function fileView($event, DiscussionTopicFile $file)
    {
        return response()->file(public_path("storage/image/discussion/$event/$file->path"));
    }

    /**
     * @param DiscussionTopic $discussion
     * @param DiscussionTopicFile $file
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadFileEdit(DiscussionTopic $discussion, DiscussionTopicFile $file)
    {
        if($discussion->department_id)
            $this->authorize('DEPARTMENT-SPECIFIC', $discussion->department_id);
        elseif (!Auth::user()->can('ADMIN') && Auth::user()->can('EVENT'))
            $this->authorize('EVENT-SPECIFIC', $discussion->event_id);
        else
            $this->authorize('ADMIN');

        $data['file'] = $file;
        $data['discussion'] = $discussion;
        return view('laralum.event.upload-edit', $data);
    }

    /**
     * @param DiscussionTopic $discussion
     * @param DiscussionTopicFile $file
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadFileUpdate(DiscussionTopic $discussion, DiscussionTopicFile $file, Request $request)
    {
        if($discussion->department_id)
            $this->authorize('DEPARTMENT-SPECIFIC', $discussion->department_id);
        elseif (!Auth::user()->can('ADMIN') && Auth::user()->can('EVENT'))
            $this->authorize('EVENT-SPECIFIC', $discussion->event_id);
        else
            $this->authorize('ADMIN');

        $this->validate($request, [
            'name' => 'required',
        ]);

        $file->name = $request->name;
        $file->note = $request->note;
        if($request->hasFile('file')) {
            $request->file->store("public/image/discussion/$discussion->id");
            if($file->path) Storage::delete("public/image/discussion/$discussion->id/$file->path");
            $file->path = $request->file->hashName();
        }
        $file->save();
        return redirect()->route('Laralum::event::upload', ['discussion' => $discussion->id])->with('success', 'File Updated Successfully');
    }

    /**
     * @param DiscussionTopic $discussion
     * @param DiscussionTopicFile $file
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadFileDelete(DiscussionTopic $discussion, DiscussionTopicFile $file)
    {
        if($discussion->department_id)
            $this->authorize('DEPARTMENT-SPECIFIC', $discussion->department_id);
        elseif (!Auth::user()->can('ADMIN') && Auth::user()->can('EVENT'))
            $this->authorize('EVENT-SPECIFIC', $discussion->event_id);
        else
            $this->authorize('ADMIN');

        $file->delete();

        if($discussion->department_id)
            return redirect()->route('Laralum::department::event::list', ['department' => $discussion->department_id])->with('success', 'Uploaded File Deleted Successfully ');
        else
            return redirect()->route('Laralum::event::list')->with('success', 'Uploaded File Deleted Successfully ');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function reorder(Request $request)
    {
        $modelName = $request->model;
        $table = $modelName::getModel()->getTable();

        if (!empty($request->data)){
            $dataWithOderKey =  array_flip(array_map(function($el) use ($request) { return $el + $request->orderStart; }, array_flip($request->data)));
            $this->orderReArrange($dataWithOderKey, $table, $request->field);
        }
        else
            return 'No Data Found for Re-order';

        return 'Order Updated Successfully';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function jsonSearch(Request $request)
    {
        return GalleryImages::allWithOptionalFilter(false, $request->category_id, $request->status);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function galleryView()
    {
        $data['images'] = GalleryImages::allWithOptionalFilter(false, 5218, 1);
        return view('frontend.galary', $data);
    }
}
