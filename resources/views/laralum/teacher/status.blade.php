@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Status</div>
    </div>
@endsection
@section('title', 'Status')
@section('icon', "list")
@section('subtitle', 'Status')
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="sixteen wide column">
                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($statuses as $status)
                        <tr>
                            <td> {{ $status->name }}</td>
                            <td>
                                <a href='{{ route('Laralum::teacher::status::edit', ['status' => $status->id]) }}' class="item">
                                    <i class="edit icon"></i>Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="sixteen wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::teacher::status::create') }}">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." required>
                        </div>
                        <br/>

                        <button type="submit" class="ui blue submit button">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
