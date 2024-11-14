@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(isset($uri)) <a class="section" href="{{ route("Laralum::$uri::link::list", [$uri => $uriValue]) }}">Link List</a>
        @else <a class="section" href="{{ route('Laralum::link::list') }}">Link List</a> @endif

        <i class="right angle icon divider"></i>
        <div class="active section">Link Create</div>
    </div>
@endsection
@section('title', 'Link Create')
@section('icon', "plus")
@section('subtitle', 'Link Create')
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::link::store') }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Label</label>
                            <input type="text"  name="label" placeholder="Label..." required>
                        </div>

                        <div class="field required">
                            <label>Type</label>
                            <select name="type_id" required>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(isset($uri))
                            <input type="hidden" name="department_id" value="{{$uriValue}}">
                        @endif

                        <div class="field required">
                            <label>Link URL</label>
                            <input type="text"  name="link_url" placeholder="Link URL..." required>
                        </div>

                        <div class="field">
                            <label>Target</label>
                            <select name="target">
                                <option value="_self">_self</option>
                                <option value="_blank">_blank</option>
                                <option value="_parent">_parent</option>
                                <option value="_top">_top</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>CSS Class</label>
                            <input type="text"  name="css_class" placeholder="CSS Class...">
                        </div>

                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" name="enabled" tabindex="0" class="hidden" checked>
                                <label>Enabled</label>
                            </div>
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
