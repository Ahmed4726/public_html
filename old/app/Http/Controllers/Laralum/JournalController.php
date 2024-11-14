<?php

namespace App\Http\Controllers\Laralum;

use App\Department;
use App\Faculty;
use App\Http\Controllers\Controller;
use App\Journal;
use App\JournalContent;
use App\UserDepartment;
use App\UserFaculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

class JournalController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $restriction = [];

        if (Auth::user()->hasRole('DEPARTMENT')) {
            $this->authorize('DEPARTMENT');
            $userDepartment = UserDepartment::whereUserId(Auth::user()->id)->get(['department_id'])->pluck(['department_id']);
            $data['departments'] = Department::whereIn('id', $userDepartment)->get();
            $restriction['department'] = $userDepartment;
        }

        if (Auth::user()->hasRole('DEAN')) {
            $this->authorize('DEAN');
            $userFaculty = UserFaculty::whereUserId(Auth::user()->id)->get(['faculty_id'])->pluck(['faculty_id']);
            $data['faculties'] = Faculty::whereIn('id', $userFaculty)->get();
            $restriction['faculty'] = $userFaculty;
        }

        if (Auth::user()->can('ADMIN')) {
            $this->authorize('ADMIN');
            $data['departments'] = Department::all();
            $data['faculties'] = Faculty::all();
        }

        $data['journals'] = Journal::allWithOptionalFilter($request->search, $request->department_id, $request->faculty_id, false, ['department', 'faculty'], $restriction)->appends($request->all());
        return view('laralum.journal.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        if (!Auth::user()->can('ADMIN') && Auth::user()->can('DEPARTMENT')) {
            $userDepartment = UserDepartment::whereUserId(Auth::user()->id)->get(['department_id'])->pluck(['department_id']);
            $data['departments'] = Department::whereIn('id', $userDepartment)->get();
        }

        if (!Auth::user()->can('ADMIN') && Auth::user()->can('DEAN')) {
            $userFaculty = UserFaculty::whereUserId(Auth::user()->id)->get(['faculty_id'])->pluck(['faculty_id']);
            $data['faculties'] = Faculty::whereIn('id', $userFaculty)->get();
        }

        if (Auth::user()->can('ADMIN')) {
            $data['departments'] = Department::all();
            $data['faculties'] = Faculty::all();
        }

        return view('laralum.journal.create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $redirectParam = [];
        $journal = new Journal();
        $journal->title = $request->title;
        $journal->department_id = null;
        $journal->faculty_id = null;

        if ($request->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $request->department_id);

            if (Auth::user()->hasRole('DEPARTMENT') && Journal::whereDepartmentId($request->department_id)->first()) {
                return redirect()->back()->with('error', 'Journal Already Exist for the Department');
            }

            $journal->department_id = $request->department_id;
            $redirectParam['department_id'] = $request->department_id;
        }

        if ($request->faculty_id) {
            $this->authorize('DEAN-SPECIFIC', $request->faculty_id);

            if (Auth::user()->hasRole('DEAN') && Journal::whereFacultyId($request->faculty_id)->first()) {
                return redirect()->back()->with('error', 'Journal Already Exist for the Faculty');
            }

            $journal->faculty_id = $request->faculty_id;
            $redirectParam['faculty_id'] = $request->faculty_id;
        }

        $journal->save();
        return redirect()->route('Laralum::journal::list', $redirectParam)->with('success', 'Journal Created Successfully');
    }

    /**
     * @param Journal $journal
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Journal $journal)
    {
        if ($journal->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $journal->department_id);
        }
        if ($journal->faculty_id) {
            $this->authorize('FACULTY-SPECIFIC', $journal->faculty_id);
        }

        $journal->delete();
        return redirect()->route('Laralum::journal::list')->with('success', 'Journal Deleted Successfully');
    }

    /**
     * @param Journal $journal
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Journal $journal)
    {
        if (!Auth::user()->can('ADMIN') && Auth::user()->can('DEPARTMENT')) {
            $userDepartment = UserDepartment::whereUserId(Auth::user()->id)->get(['department_id'])->pluck(['department_id']);
            $data['departments'] = Department::whereIn('id', $userDepartment)->get();
        } elseif (!Auth::user()->can('ADMIN') && Auth::user()->can('DEAN')) {
            $userFaculty = UserFaculty::whereUserId(Auth::user()->id)->get(['faculty_id'])->pluck(['faculty_id']);
            $data['faculties'] = Faculty::whereIn('id', $userFaculty)->get();
        } else {
            $data['departments'] = Department::all();
            $data['faculties'] = Faculty::all();
        }

        $data['journal'] = $journal;
        return view('laralum.journal.edit', $data);
    }

    /**
     * @param Journal $journal
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Journal $journal, Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $redirectParam = [];
        $journal->title = $request->title;
        $journal->department_id = null;
        $journal->faculty_id = null;

        if ($request->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $request->department_id);
            $journal->department_id = $request->department_id;
            $redirectParam['department_id'] = $request->department_id;
        }

        if ($request->faculty_id) {
            $this->authorize('DEAN-SPECIFIC', $request->faculty_id);
            $journal->faculty_id = $request->faculty_id;
            $redirectParam['faculty_id'] = $request->faculty_id;
        }

        $journal->save();
        return redirect()->route('Laralum::journal::list', $redirectParam)->with('success', 'Journal Updated Successfully');
    }

    /**
     * @param Journal $journal
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function contentList(Journal $journal, Request $request)
    {
        if ($journal->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $journal->department_id);
        } elseif ($journal->faculty_id) {
            $this->authorize('DEAN-SPECIFIC', $journal->faculty_id);
        }

        $data['journal'] = $journal;
        $data['contents'] = JournalContent::allWithOptionalFilter($request->search, $journal->id)->appends($request->all());
        (count(array_filter($request->except('page'))) == 0) ? $data['sortable'] = true : $data['sortable'] = false;
        return view('laralum.journal.content.index', $data);
    }

    /**
     * @param Journal $journal
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function contentCreate(Journal $journal)
    {
        if ($journal->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $journal->department_id);
        } elseif ($journal->faculty_id) {
            $this->authorize('DEAN-SPECIFIC', $journal->faculty_id);
        }

        $data['journal'] = $journal;
        return view('laralum.journal.content.create', $data);
    }

    /**
     * @param Journal $journal
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function contentStore(Journal $journal, Request $request)
    {
        if ($journal->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $journal->department_id);
        } elseif ($journal->faculty_id) {
            $this->authorize('DEAN-SPECIFIC', $journal->faculty_id);
        }

        $this->validate($request, [
            'title' => 'required',
        ]);

        $content = new JournalContent();
        $content->title = $request->title;
        $content->author = $request->author;
        $content->co_author = $request->co_author;
        $content->volume = $request->volume;
        $content->external_link = $request->external_link;
        if ($request->sorting_order) {
            $content->sorting_order = $request->sorting_order;
        }

        if ($request->hasFile('file')) {
            $request->file->store('public/image/journal');
            $content->path = $request->file->hashName();
        }

        $journal->contents()->save($content);
        return redirect()->route('Laralum::journal::content::list', ['journal' => $journal->id])->with('success', 'Journal Content Created Successfully');
    }

    /**
     * @param Journal $journal
     * @param JournalContent $content
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function contentDelete(Journal $journal, JournalContent $content)
    {
        if ($journal->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $journal->department_id);
        } elseif ($journal->faculty_id) {
            $this->authorize('DEAN-SPECIFIC', $journal->faculty_id);
        }

        $content->delete();
        return redirect()->route('Laralum::journal::content::list', ['journal' => $journal->id])->with('success', 'Journal Content Deleted Successfully');
    }

    /**
     * @param Journal $journal
     * @param JournalContent $content
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function contentEdit(Journal $journal, JournalContent $content)
    {
        if ($journal->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $journal->department_id);
        } elseif ($journal->faculty_id) {
            $this->authorize('DEAN-SPECIFIC', $journal->faculty_id);
        }

        $data['content'] = $content;
        $data['journal'] = $journal;
        return view('laralum.journal.content.edit', $data);
    }

    /**
     * @param Journal $journal
     * @param JournalContent $content
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function contentUpdate(Journal $journal, JournalContent $content, Request $request)
    {
        if ($journal->department_id) {
            $this->authorize('DEPARTMENT-SPECIFIC', $journal->department_id);
        } elseif ($journal->faculty_id) {
            $this->authorize('DEAN-SPECIFIC', $journal->faculty_id);
        }

        $this->validate($request, [
            'title' => 'required',
        ]);

        $content->title = $request->title;
        $content->author = $request->author;
        $content->co_author = $request->co_author;
        $content->volume = $request->volume;
        $content->external_link = $request->external_link;
        $content->sorting_order = $request->sorting_order;

        if ($request->hasFile('file')) {
            $request->file->store('public/image/journal');
            if ($content->path) {
                Storage::delete("public/image/journal/$content->path");
            }
            $content->path = $request->file->hashName();
        }

        $journal->contents()->save($content);
        return redirect()->route('Laralum::journal::content::list', ['journal' => $journal->id])->with('success', 'Journal Content Updated Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search(Request $request)
    {
        return [
            'content' => JournalContent::allWithOptionalFilter($request->search, false, false, $request->relation, $request->department_id, $request->faculty_id, true, ['volume' => $request->volume]),
            'volume' => ($request->department_id || $request->faculty_id) ? JournalContent::allWithOptionalFilter($request->search, false, 500, $request->relation, $request->department_id, $request->faculty_id, true)->pluck('volume')->unique() : []
        ];
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists()
    {
        $data['faculties'] = Faculty::all();
        return view('frontend.journal.list', $data);
    }

    /**
     * @param JournalContent $content
     * @return mixed
     */
    public function contentDownload(JournalContent $content)
    {
        return response()->file(public_path("storage/image/journal/$content->path"));
    }
}
