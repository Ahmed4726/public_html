@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::users') }}">{{ trans('laralum.user_list') }}</a>
    <i class="right angle icon divider"></i>
    <div class="active section">{{ trans('laralum.users_edit_roles_title') }}</div>
</div>
@endsection
@section('title', trans('laralum.users_edit_roles_title'))
@section('icon', "star")
@section('subtitle', trans('laralum.users_edit_roles_subtitle', ['email' => $user->email]))

@section('content')
<div class="ui doubling stackable grid container">
    <div class="two wide column"></div>
    <div class="twelve wide column">
        <div class="ui very padded segment">
            <form method="POST" class="ui form">
                {{ csrf_field() }}

                @foreach($roles as $role)
                <div class="inline field">
                    <div class="ui slider checkbox">
                        <input type="checkbox" @if($role->id == 1 || $role->id == 2) readonly @endif
                        name="{{$role->id}}" tabindex="0" class="hidden" @if($user->existRole($role->id)) checked
                        @endif>
                        <label>
                            {{$role->type}}
                            @if($role->id == 1 || $role->id == 2)
                            <i data-variation="wide" class="red lock icon pop" data-position="right center"
                                data-title="Unassignable Role" data-content="This role can't be modified"></i>
                            @endif

                            @if($role->id == 4 && $user->existRole(4)) <a
                                href="{{route("Laralum::users_role_faculty_assign", ['user' => $user->id])}}">Assign
                                Faculty</a> @endif
                            @if($role->id == 5 && $user->existRole(5)) <a
                                href="{{route("Laralum::users_role_hall_assign", ['user' => $user->id])}}">Assign
                                Hall</a> @endif
                            @if($role->id == 6 && $user->existRole(6)) <a
                                href="{{route("Laralum::users_role_department_assign", ['user' => $user->id])}}">Assign
                                Department</a> @endif
                            @if($role->id == 7 && $user->existRole(7)) <a
                                href="{{route("Laralum::users_role_center_assign", ['user' => $user->id])}}">Assign
                                Center</a> @endif
                            @if($role->id == 10 && $user->existRole(10)) <a
                                href="{{route("Laralum::users_role_event_assign", ['user' => $user->id])}}">Assign
                                Event</a> @endif
                            @if($role->id == 17 && $user->existRole(17))
                            <a href="{{route("Laralum::users_role_admission_session_assign", ['user' => $user->id])}}">
                                Assign Session
                            </a>
                            @endif

                        </label>
                    </div>
                </div>

                @if($role->id == 3 && $user->existRole(3) && $user->teachers()->exists())
                <table class="ui small celled striped table">
                    <thead>
                        <tr>
                            <th colspan="2">
                                Teachers
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->name }}</td>
                            <td class="right aligned"><a
                                    href="{{route('Laralum::users_role_teacher_unassign', ['user' => $user->id, 'teacher' => $teacher->id])}}">Un-Assign
                                    Teacher</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                @if($role->id == 4 && $user->existRole(4) && $user->faculties()->exists())
                <table class="ui small celled striped table">
                    <thead>
                        <tr>
                            <th colspan="2">
                                Faculty
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->faculties as $faculty)
                        <tr>
                            <td>{{ $faculty->name }}</td>
                            <td class="right aligned"><a
                                    href="{{route('Laralum::users_role_faculty_unassign', ['user' => $user->id, 'faculty' => $faculty->id])}}">Un-Assign
                                    Faculty</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                @if($role->id == 5 && $user->existRole(5) && $user->halls()->exists())
                <table class="ui small celled striped table">
                    <thead>
                        <tr>
                            <th colspan="2">
                                Hall
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->halls as $hall)
                        <tr>
                            <td>{{ $hall->name }}</td>
                            <td class="right aligned"><a
                                    href="{{route('Laralum::users_role_hall_unassign', ['user' => $user->id, 'hall' => $hall->id])}}">Un-Assign
                                    Hall</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                @if($role->id == 6 && $user->existRole(6) && $user->departments()->exists())
                <table class="ui small celled striped table">
                    <thead>
                        <tr>
                            <th colspan="2">
                                Department
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->departments as $department)
                        <tr>
                            <td>{{ $department->name }}</td>
                            <td class="right aligned"><a
                                    href="{{route('Laralum::users_role_department_unassign', ['user' => $user->id, 'department' => $department->id])}}">Un-Assign
                                    Department</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                @if($role->id == 7 && $user->existRole(7) && $user->centers()->exists())
                <table class="ui small celled striped table">
                    <thead>
                        <tr>
                            <th colspan="2">
                                Center / Office
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->centers as $center)
                        <tr>
                            <td>{{ $center->name }}</td>
                            <td class="right aligned"><a
                                    href="{{route('Laralum::users_role_center_unassign', ['user' => $user->id, 'center' => $center->id])}}">Un-Assign
                                    Center / Office</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                @if($role->id == 10 && $user->existRole(10) && $user->events()->exists())
                <table class="ui small celled striped table">
                    <thead>
                        <tr>
                            <th colspan="2">
                                Event
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->events as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td class="right aligned"><a
                                    href="{{route('Laralum::users_role_event_unassign', ['user' => $user->id, 'event' => $event->id])}}">Un-Assign
                                    Event</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                @if($role->id == 17 && $user->existRole(17) && $user->admissionSessions->isNotEmpty())
                <table class="ui small celled striped table">
                    <thead>
                        <tr>
                            <th colspan="2">
                                Admission Session
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->admissionSessions as $session)
                        <tr>
                            <td>{{ $session->name }}</td>
                            <td class="right aligned"><a
                                    href="{{route('Laralum::users_role_admission_session_unassign', ['user' => $user->id, 'session' => $session->id])}}">Un-Assign
                                    Session</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                @endforeach

                <br>
                <div class="field">
                    <button type="submit" class="ui blue submit button">{{ trans('laralum.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="four wide column"></div>
</div>
@endsection