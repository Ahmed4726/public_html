<?php

namespace App\Http\Controllers\Laralum;

use App\AdmissionSession;
use App\Center;
use App\Department;
use App\Event;
use App\Faculty;
use App\Hall;
use App\Role;
use App\Setting;
use App\Teacher;
use App\User;
use App\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role_User;
use Laralum;
use Auth;
use Hash;

class UsersController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('ADMIN');
        $data['halls'] = Hall::all();
        $data['centers'] = Center::all();
        $data['departments'] = Department::all();
        $data['faculties'] = Faculty::all();
        $data['types'] = UserType::all();
        $data['roles'] = Role::orderBy('id', 'asc')->get();
        $data['users'] = User::allWithOptionalFilter($request->search, $request->state_id, $request->role_id, $request->faculty_id, $request->department_id, $request->center_id, $request->hall_id, false, ['stateInfo', 'roles'])->appends($request->all());
        return view('laralum/users/index', $data);
    }

    public function show($id)
    {
        Laralum::permissionToAccess('laralum.users.access');

        // Find the user
        $user = Laralum::user('id', $id);

        // Return the view
        return view('laralum/users/show', ['user' => $user]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('ADMIN');
        $data['types'] = UserType::all();
        return view('laralum/users/create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:users,email',
            'state_id' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->state_id = $request->state_id;
        $user->state = UserType::find($request->state_id, ['name'])->name;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->roles()->attach(1);

        return redirect()->route('Laralum::users')->with('success', 'User Created Successfully');
    }

    /**
     * @param Teacher $teacher
     * @return bool
     */
    public function createUerForTeacher(Teacher $teacher)
    {
        $setting = Setting::first();
//        $defaultPassword = ($setting->default_password_new_user) ? $setting->default_password_new_user : '123456';
        $user = new User();
        $user->name = $teacher->name;
        $user->email = $teacher->email;
        $user->state_id = 1;
        $user->state = UserType::find(1, ['name'])->name;
//        $user->password = Hash::make($defaultPassword);
        $user->password = Hash::make('@' . strstr($teacher->email, '@', true) . '20#');
        $user->save();
        $user->roles()->attach([1, 3]);
        $user->teachers()->attach($teacher->id);
        return true;
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('ADMIN');
        $data['types'] = UserType::all();
        $data['user'] = $user;
        return view('laralum/users/edit', $data);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(User $user, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
            'state_id' => 'required',
        ]);

        $user->name = $request->name;
        $user->state_id = $request->state_id;
        $user->state = UserType::find($request->state_id, ['name'])->name;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('Laralum::users')->with('success', 'User Updated Successfully');
    }

    /**
     * @param User $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function editRoles(User $id)
    {
        $this->authorize('ADMIN');
        $data['faculties'] = Faculty::all();
        $data['roles'] = Role::orderBy('id', 'asc')->get();
        $data['user'] = $id;
        return view('laralum/users/roles', $data);
    }

    /**
     * @param User $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function setRoles(User $id, Request $request)
    {
        $this->authorize('ADMIN');
        $roles = array_keys($request->except('_token'));

        if (!in_array(4, $roles)) {
            $id->faculties()->detach();
        }
        if (!in_array(5, $roles)) {
            $id->halls()->detach();
        }
        if (!in_array(6, $roles)) {
            $id->departments()->detach();
        }
        if (!in_array(7, $roles)) {
            $id->centers()->detach();
        }

        $id->roles()->sync($roles);
        return redirect()->route('Laralum::users_roles', ['id' => $id])->with('success', trans('laralum.msg_user_roles_edited'));
    }

    public function checkRole($user_id, $role_id)
    {
        Laralum::permissionToAccess('laralum.users.access');

        // This function returns true if the specified user is found in the specified role and false if not

        if (Role_User::whereUser_idAndRole_id($user_id, $role_id)->first()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteRel($user_id, $role_id)
    {
        Laralum::permissionToAccess('laralum.users.access');

        $rel = Role_User::whereUser_idAndRole_id($user_id, $role_id)->first();
        if ($rel) {
            $rel->delete();
        }
    }

    public function addRel($user_id, $role_id)
    {
        Laralum::permissionToAccess('laralum.users.access');

        $rel = Role_User::whereUser_idAndRole_id($user_id, $role_id)->first();
        if (!$rel) {
            $rel = new Role_User;
            $rel->user_id = $user_id;
            $rel->role_id = $role_id;
            $rel->save();
        }
    }

    public function destroy(User $user)
    {
        $this->authorize('ADMIN');
        $user->delete();
        return redirect()->route('Laralum::users')->with('success', 'User Deleted Successfully');
    }

    public function editSettings()
    {
        Laralum::permissionToAccess('laralum.users.access');

        // Check permissions
        Laralum::permissionToAccess('laralum.users.settings');

        // Get the user settings
        $row = Laralum::userSettings();

        // Update the settings
        $data_index = 'users_settings';
        require 'Data/Edit/Get.php';

        return view('laralum/users/settings', [
            'row' => $row,
            'fields' => $fields,
            'confirmed' => $confirmed,
            'empty' => $empty,
            'encrypted' => $encrypted,
            'hashed' => $hashed,
            'masked' => $masked,
            'table' => $table,
            'code' => $code,
            'wysiwyg' => $wysiwyg,
            'relations' => $relations,
        ]);
    }

    public function updateSettings(Request $request)
    {
        Laralum::permissionToAccess('laralum.users.access');

        // Check permissions
        Laralum::permissionToAccess('laralum.users.settings');

        // Get the user settings
        $row = Laralum::userSettings();

        // Update the settings
        $data_index = 'users_settings';
        require 'Data/Edit/Save.php';

        // Return a redirect
        return redirect()->route('Laralum::users')->with('success', trans('laralum.msg_user_update_settings'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeList()
    {
        $this->authorize('ADMIN');
        $data['types'] = UserType::all();
        return view('laralum.users.type.index', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeStore(Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $type = new UserType();
        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::users::type::list')->with('success', 'User Type Created Successfully');
    }

    /**
     * @param UserType $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeEdit(UserType $type)
    {
        $this->authorize('ADMIN');
        $data['type'] = $type;
        return view('laralum.users.type.edit', $data);
    }

    /**
     * @param UserType $type
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function typeUpdate(UserType $type, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $type->name = $request->name;
        $type->save();
        return redirect()->route('Laralum::users::type::list')->with('success', 'User Type Updated Successfully');
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleDepartmentAssign(User $user)
    {
        $this->authorize('ADMIN');
        $data['faculties'] = Faculty::all();
        $data['user'] = $user;
        return view('laralum.users.role.assign-department', $data);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleDepartmentAssignStore(User $user, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'department_id' => 'required'
        ]);

        if (!$user->departments->contains(function ($value, $key) use ($request) {
            return $value->id == $request->department_id;
        })) {
            $user->departments()->attach($request->department_id);
            return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Department Assigned Successfully');
        } else {
            return redirect()->route('Laralum::users_role_department_assign', ['user' => $user->id])->with('error', 'This Department Already Assigned');
        }
    }

    /**
     * @param User $user
     * @param Department $department
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleDepartmentUnassign(User $user, Department $department)
    {
        $this->authorize('ADMIN');
        $user->departments()->detach($department->id);
        return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Department Un-Assigned Successfully');
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleFacultyAssign(User $user)
    {
        $this->authorize('ADMIN');
        $data['faculties'] = Faculty::all();
        $data['user'] = $user;
        return view('laralum.users.role.assign-faculty', $data);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleFacultyAssignStore(User $user, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'faculty_id' => 'required'
        ]);

        if (!$user->faculties->contains(function ($value, $key) use ($request) {
            return $value->id == $request->faculty_id;
        })) {
            $user->faculties()->attach($request->faculty_id);
            return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Faculty Assigned Successfully');
        } else {
            return redirect()->route('Laralum::users_role_faculty_assign', ['user' => $user->id])->with('error', 'This Faculty Already Assigned');
        }
    }

    /**
     * @param User $user
     * @param Faculty $faculty
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleFacultyUnassign(User $user, Faculty $faculty)
    {
        $this->authorize('ADMIN');
        $user->faculties()->detach($faculty->id);
        return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Faculty Un-Assigned Successfully');
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleHallAssign(User $user)
    {
        $this->authorize('ADMIN');
        $data['halls'] = Hall::all();
        $data['user'] = $user;
        return view('laralum.users.role.assign-hall', $data);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleHallAssignStore(User $user, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'hall_id' => 'required'
        ]);

        if (!$user->halls->contains(function ($value, $key) use ($request) {
            return $value->id == $request->hall_id;
        })) {
            $user->halls()->attach($request->hall_id);
            return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Hall Assigned Successfully');
        } else {
            return redirect()->route('Laralum::users_role_hall_assign', ['user' => $user->id])->with('error', 'This Hall Already Assigned');
        }
    }

    /**
     * @param User $user
     * @param Hall $hall
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleHallUnassign(User $user, Hall $hall)
    {
        $this->authorize('ADMIN');
        $user->halls()->detach($hall->id);
        return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Hall Un-Assigned Successfully');
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleCenterAssign(User $user)
    {
        $this->authorize('ADMIN');
        $data['centers'] = Center::all();
        $data['user'] = $user;
        return view('laralum.users.role.assign-center', $data);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleCenterAssignStore(User $user, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'center_id' => 'required'
        ]);

        if (!$user->centers->contains(function ($value, $key) use ($request) {
            return $value->id == $request->center_id;
        })) {
            $user->centers()->attach($request->center_id);
            return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Center Assigned Successfully');
        } else {
            return redirect()->route('Laralum::users_role_center_assign', ['user' => $user->id])->with('error', 'This Center Already Assigned');
        }
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleEventAssign(User $user)
    {
        $this->authorize('ADMIN');
        $data['events'] = Event::all();
        $data['user'] = $user;
        return view('laralum.users.role.assign-event', $data);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleAdmissionSessionAssign(User $user)
    {
        $this->authorize('ADMIN');
        $data['sessions'] = AdmissionSession::all();
        $data['user'] = $user;
        return view('laralum.users.role.assign-admission-session', $data);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleEventAssignStore(User $user, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'event_id' => 'required'
        ]);

        if (!$user->events->contains(function ($value, $key) use ($request) {
            return $value->id == $request->event_id;
        })) {
            $user->events()->attach($request->event_id);
            return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Event Assigned Successfully');
        } else {
            return redirect()->route('Laralum::users_role_event_assign', ['user' => $user->id])->with('error', 'This Event Already Assigned');
        }
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleAdmissionSessionAssignStore(User $user, Request $request)
    {
        $this->authorize('ADMIN');
        $this->validate($request, [
            'session_id' => 'required'
        ]);

        if (!$user->admissionSessions->contains(function ($value, $key) use ($request) {
            return $value->id == $request->session_id;
        })) {
            $user->admissionSessions()->attach($request->session_id);
            return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Admission Session Assigned Successfully');
        } else {
            return redirect()->route('Laralum::users_role_admission_session_assign', ['user' => $user->id])->with('error', 'This Admission Session Already Assigned');
        }
    }

    /**
     * @param User $user
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleEventUnassign(User $user, Event $event)
    {
        $this->authorize('ADMIN');
        $user->events()->detach($event->id);
        return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Event Un-Assigned Successfully');
    }

    /**
     * @param User $user
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleAdmissionSessionUnassign(User $user, AdmissionSession $session)
    {
        $this->authorize('ADMIN');
        $user->admissionSessions()->detach($session->id);
        return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Admission Session Un-Assigned Successfully');
    }

    /**
     * @param User $user
     * @param Center $center
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleCenterUnassign(User $user, Center $center)
    {
        $this->authorize('ADMIN');
        $user->centers()->detach($center->id);
        return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Center Un-Assigned Successfully');
    }

    /**
     * @param User $user
     * @param Teacher $teacher
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roleTeacherUnassign(User $user, Teacher $teacher)
    {
        $this->authorize('ADMIN');
        $user->teachers()->detach($teacher->id);
        return redirect()->route('Laralum::users_roles', ['id' => $user->id])->with('success', 'Teacher Un-Assigned Successfully');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function reOrder(Request $request)
    {
        return $request->all();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePassword()
    {
        return view('laralum.users.change-password');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePasswordUpdate(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        if (Hash::check($request->current_password, Auth::user()->password)) {
            $user = Auth::user();
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('Laralum::change-password')->with('success', 'Password Updated Successfully');
        } else {
            return redirect()->route('Laralum::change-password')->with('error', 'Current Password Not Matched');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function forceLogin($id)
    {
        $this->authorize('ADMIN');
        Auth::loginUsingId($id);
        return redirect()->route('Laralum::dashboard');
    }
}
