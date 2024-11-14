@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::dashboard') }}">Dashboard</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Change Password</div>
    </div>
@endsection
@section('title', 'Change Password')
@section('icon', "edit")
@section('subtitle', Auth::user()->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::update-password') }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="required field">
                            <label>Current Password</label>
                            <input type="password"  name="current_password" placeholder="Current Password ..." required>
                        </div>

                        <div class="required field">
                            <label>New Password</label>
                            <input type="password"  name="password" placeholder="New Password ..." required>
                        </div>

                        <div class="required field">
                            <label>Confirm New Password</label>
                            <input type="password"  name="password_confirmation" placeholder="Confirm New Password ..." required>
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

