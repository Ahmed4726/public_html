@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::officer::type::list') }}">Officers / Staffs Type List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Officers / Staffs Type Edit</div>
    </div>
@endsection
@section('title', 'Officers / Staffs Type Edit')
@section('icon', "edit")
@section('subtitle', $type->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::officer::type::update', ['type' => $type->id]) }}">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." value="{{$type->name}}" required>
                        </div>

                        <div class="fields required">
                            <div class="five wide field">
                                <label>Image Width</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="width" placeholder="Width..." value="{{$type->width}}" required>
                                    <div class="ui basic label">
                                        px
                                    </div>
                                </div>
                            </div>
                            <div class="five wide field">
                                <label>Image Height</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="height" placeholder="Height..." value="{{$type->height}}" required>
                                    <div class="ui basic label">
                                        px
                                    </div>
                                </div>
                            </div>
                            <div class="six wide field">
                                <label>Max Image Size</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="max_size" placeholder="Max Size..." value="{{$type->max_size}}" required>
                                    <div class="ui basic label">
                                        kb
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>

                        <button type="submit" class="ui blue submit button">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
