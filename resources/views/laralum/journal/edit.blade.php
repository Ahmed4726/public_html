@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::journal::list') }}">Journal List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Journal Edit</div>
    </div>
@endsection
@section('title', 'Journal Edit')
@section('icon', "edit")
@section('subtitle', 'Journal Edit')
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::journal::update', ['journal' => $journal->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Title</label>
                            <input type="text"  name="title" placeholder="Title..." value="{{$journal->title}}" required>
                        </div>

                        @if(isset($departments)) <div class="field">
                            <label>Department</label>
                            <select name="department_id">
                                <option value=""> Please Select a Department</option>
                                @foreach($departments as $department)
                                    <option @if($journal->department_id == $department->id) selected @endif value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div> @endif

                        @if(isset($faculties)) <div class="field">
                            <label>Faculty</label>
                            <select name="faculty_id">
                                <option value=""> Please Select a Faculty</option>
                                @foreach($faculties as $faculty)
                                    <option @if($journal->faculty_id == $faculty->id) selected @endif value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div> @endif

                        <br>
                        <button type="submit" class="ui blue submit button">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

