@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::student-email-apply::admission-session::list') }}">Employee Type
        List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Employee Type Edit</div>
</div>
@endsection
@section('title', 'Employee Type Edit')
@section('icon', "edit")
@section('subtitle', $type->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST"
                action="{{ route('Laralum::employee-email::type::update', ['type' => $type->id]) }}">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." value="{{$type->name}}" required>
                    </div>

                    <div class="inline field">
                        <div class="ui slider checkbox">
                            <input type="checkbox" name="is_visible" @if ($type->is_visible) checked @endif
                            value="1" tabindex="0" class="hidden">
                            <label>Is Visible</label>
                        </div>
                    </div>

                    <br />

                    <button type="submit" class="ui blue submit button">Update</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection