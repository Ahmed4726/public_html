@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::internet-complain::team::list') }}">Internet Complain Team
        List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Internet Complain Team Edit</div>
</div>
@endsection
@section('title', 'Internet Complain Team Edit')
@section('icon', "edit")
@section('subtitle', $team->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST"
                action="{{ route('Laralum::internet-complain::team::update', ['team' => $team->id]) }}">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." value="{{$team->name}}" required>
                    </div>

                    <div class="field">
                        <label>Internet Staff</label>
                        <select name="user_id">
                            <option value="">Please select a internet staff</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label>Existing Staff</label>
                        @foreach ($team->users as $user)
                        <div class="mt-2">
                            {{$user->name}} <a
                                href="{{ route('Laralum::internet-complain::team::detach', ['user' => $user->id, 'team' => $team->id ]) }}"><i
                                    class="icon delete pointing red"></i></a>
                        </div>
                        @endforeach
                    </div>
                    <br />

                    <button type="submit" class="ui blue submit button">Update</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection