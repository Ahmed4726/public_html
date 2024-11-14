@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">All Notice Type List</div>
    </div>
@endsection
@section('title', 'All Notice Type List')
@section('icon', "list")
@section('subtitle', 'All Notice Type List')
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="sixteen wide column">
                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Max Size</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($events as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->max_size }} KB</td>
                            <td>
                                <div class="ui blue top icon left pointing dropdown button">
                                    Edit
                                    <div class="menu">
                                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                                        <a href="{{ route('Laralum::event::type::edit', ['event' => $event->id]) }}" class="item">
                                            <i class="edit icon"></i>
                                            Basic Edit
                                        </a>

                                        <a href='{{route('Laralum::event::list', ['event_id' => $event->id])}}' class="item">
                                            <i class="list icon"></i>
                                            All Event
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="sixteen wide column">
                <form class="ui form" method="POST" action="{{ route('Laralum::event::type::store') }}">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text" name="name" placeholder="Name" required>
                        </div>

                        <div class="fields ">
                            <div class="five wide field">
                                <label>Image Width</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="width" placeholder="Width...">
                                    <div class="ui basic label">
                                        px
                                    </div>
                                </div>
                            </div>
                            <div class="five wide field">
                                <label>Image Height</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="height" placeholder="Height...">
                                    <div class="ui basic label">
                                        px
                                    </div>
                                </div>
                            </div>
                            <div class="six wide field">
                                <label>Max Image Size</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="max_size" placeholder="Max Size...">
                                    <div class="ui basic label">
                                        kb
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
