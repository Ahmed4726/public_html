@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::users') }}">User List</a>
    <i class="right angle icon divider"></i>
    <a class="section" href="{{ route('Laralum::users_roles', ['id' => $user->id]) }}">Edit Roles</a>
    <i class="right angle icon divider"></i>
    <div class="active section">User Role Admission Session Assign</div>
</div>
@endsection
@section('title', 'User Role Admission Session Assign')
@section('icon', "edit")
@section('subtitle', $user->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST"
                action="{{ route('Laralum::users_role_admission_session_assign_save', ['user' => $user->id]) }}"
                enctype="multipart/form-data">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Admission Session</label>
                        <select class="ui search dropdown" required name="session_id">
                            @foreach($sessions as $session)
                            <option value="{{$session->id}}">{{$session->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <br>
                    <button type="submit" class="ui blue submit button">Assign</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection