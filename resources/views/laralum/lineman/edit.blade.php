@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::lineman::list') }}">Lineman List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Lineman Edit</div>
</div>
@endsection
@section('title', 'Lineman Edit')
@section('icon', "edit")
@section('subtitle', $lineman->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST"
                action="{{ route('Laralum::lineman::update', ['lineman' => $lineman->id]) }}">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." value="{{$lineman->name}}" required>
                    </div>

                    <div class="field">
                        <label>Details</label>
                        <textarea name="details">{{$lineman->details}}</textarea>
                    </div>
                    <br />

                    <button type="submit" class="ui blue submit button">Update</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection