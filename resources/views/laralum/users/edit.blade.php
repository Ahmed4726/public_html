@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::users') }}">User List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">User Update</div>
    </div>
@endsection
@section('title', 'User Update')
@section('icon', "edit")
@section('subtitle', $user->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::users_update', ['user' => $user->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." value="{{ $user->name }}" required>
                        </div>

                        <div class="field required">
                            <label>Status</label>
                            <select name="state_id" required>
                                @foreach($types as $type)
                                    <option @if($type->id == $user->state_id) selected="selected" @endif value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Password</label>
                            <input type="password"  name="password" placeholder="Password...">
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

