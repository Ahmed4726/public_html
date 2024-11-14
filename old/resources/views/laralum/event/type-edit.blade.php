@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::event::type::list') }}">All Notice Type List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">All Notice Type Edit</div>
    </div>
@endsection
@section('title', 'All Notice Type Edit')
@section('icon', "edit")
@section('subtitle', "$event->name")
@section('content')

    <div class="ui doubling stackable grid container" id="vue-app">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column">
                <form class="ui form" method="POST" action="{{ route('Laralum::event::type::update', ['event' => $event->id]) }}">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name" value="{{ $event->name }}" required>
                        </div>

                        <div class="fields">
                            <div class="five wide field">
                                <label>Image Width</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="width" placeholder="Width..." value="{{ $event->width }}">
                                    <div class="ui basic label">
                                        px
                                    </div>
                                </div>
                            </div>
                            <div class="five wide field">
                                <label>Image Height</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="height" placeholder="Height..." value="{{ $event->height }}">
                                    <div class="ui basic label">
                                        px
                                    </div>
                                </div>
                            </div>
                            <div class="six wide field">
                                <label>Max Image Size</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="max_size" placeholder="Max Size..." value="{{ $event->max_size }}">
                                    <div class="ui basic label">
                                        kb
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
