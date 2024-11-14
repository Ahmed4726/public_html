@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::student-email-apply::program::list') }}">Program
        List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Program Edit</div>
</div>
@endsection
@section('title', 'Program Edit')
@section('icon', "edit")
@section('subtitle', $program->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST"
                action="{{ route('Laralum::student-email-apply::program::update', ['program' => $program->id]) }}">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." value="{{$program->name}}" required>
                    </div>

                    <div class="field">
                        <label>Slug</label>
                        <input type="text" name="slug" placeholder="Slug..." value="{{$program->slug}}">
                    </div>
                    <br />

                    <button type="submit" class="ui blue submit button">Update</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection