@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::administration::list') }}">Administrative Member List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Assign Role</div>
    </div>
@endsection
@section('title', 'Administrative Member Role Assign')
@section('icon', "star")
@section('subtitle', $member->name)

@section('content')
    <div class="ui doubling stackable grid container">
        <div class="four wide column"></div>
        <div class="eight wide column">
            <div class="ui very padded segment">
                <form method="POST" class="ui form" action="{{ route('Laralum::administration::role::assign::save', ['member' => $member->id]) }}" >
                    {{ csrf_field() }}
                    @foreach($roles as $role)
                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" name="{{$role->id}}" tabindex="0" class="hidden" @if($member->hasRole($role->id)) checked @endif>
                                <label>{{$role->name}}</label>
                            </div>
                        </div>
                    @endforeach

                    <br>
                    <div class="field">
                        <button type="submit" class="ui blue submit button">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="four wide column"></div>
    </div>
@endsection
