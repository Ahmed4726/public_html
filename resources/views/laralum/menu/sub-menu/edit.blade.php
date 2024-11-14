@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::menu::list') }}">Menu List</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route('Laralum::menu::submenu::list', ['menu' => $menu->id]) }}">Sub-Menu List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Sub Menu Edit</div>
    </div>
@endsection
@section('title', 'Sub Menu Edit')
@section('icon', "edit")
@section('subtitle', $subMenu->display_text)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::menu::submenu::update', ['menu' => $menu->id, 'submenu' => $subMenu->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Display Text</label>
                            <input type="text"  name="display_text" placeholder="Display Text..." required value="{{$subMenu->display_text}}">
                        </div>

                        <div class="field required">
                            <label>URL</label>
                            <input type="text"  name="link" placeholder="URL..." required value="{{$subMenu->link}}">
                        </div>

                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" name="enabled" tabindex="0" class="hidden" @if($subMenu->enabled) checked @endif>
                                <label>Enabled</label>
                            </div>
                        </div>

                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" name="animation_enabled" tabindex="0" class="hidden"  @if($subMenu->animation_enabled) checked @endif>
                                <label>Animation Enabled</label>
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
