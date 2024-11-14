@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::users') }}">User List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">User Create</div>
    </div>
@endsection
@section('title', 'User Create')
@section('icon', "plus")
@section('subtitle', 'User Create')
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::users_store') }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="required field">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." required>
                        </div>

                        <div class="field required">
                            <label>Email (IMPORTANT: Please use an valid email which does exist. Because system will use this email for eamil exchange)</label>
                            <input type="email"  name="email" placeholder="Email..." required>
                        </div>

                        <div class="field required">
                            <label>Status</label>
                            <select name="state_id" required>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field required">
                            <label>Password</label>
                            <input type="password"  name="password" placeholder="Password..." required>
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

