@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::users') }}">User List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">User Type</div>
    </div>
@endsection
@section('title', 'User Type')
@section('icon', "list")
@section('subtitle', 'User Type')
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="sixteen wide column">
                <table class="ui small table ">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($types as $type)
                        <tr>
                            <td> {{ $type->name }}</td>
                            <td>
                                <a href='{{ route('Laralum::users::type::edit', ['type' => $type->id]) }}' class="item">
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
                <form class="ui form" method="POST" action="{{ route('Laralum::users::type::create') }}">
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
