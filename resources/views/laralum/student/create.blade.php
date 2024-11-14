@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::student::list') }}">Student List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Student Create</div>
</div>
@endsection
@section('title', 'Student Create')
@section('icon', "plus")
@section('subtitle', 'Student Create')
@section('createButton')
<a href="{{route('Laralum::student::upload')}}" class='large ui green right floated button white-text'>
    Bulk Create
</a>
@endsection
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST"
                action="{{ ($student->id) ? route('Laralum::student::update', ['student' => $student->id]) : route('Laralum::student::store') }}">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Admission Session</label>
                        <select name="admission_session_id" required>
                            <option value="">--- Admission Session ---</option>
                            @foreach($sessions as $session)
                            <option @if(old('admission_session_id', $student->admission_session_id)==$session->id)
                                selected @endif
                                value="{{ $session->id }}">{{ $session->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." value="{{old('name', $student->name)}}"
                            required>
                    </div>

                    <div class="field required">
                        <label>Program</label>
                        <select name="program_id" required>
                            <option value="">--- Program ---</option>
                            @foreach($programs as $key => $program)
                            <option @if(old('program_id', $student->program_id)==$key)
                                selected @endif
                                value="{{ $key }}">{{ $program }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field required">
                        <label>Department</label>
                        <select name="department_id" required>
                            <option value="">--- Deparment ---</option>
                            @foreach($departments as $department)
                            <option @if(old('department_id', $student->department_id)==$department->id)
                                selected @endif
                                value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field required">
                        <label>Hall</label>
                        <select name="hall_id" required>
                            <option value="">--- Hall ---</option>
                            @foreach($halls as $hall)
                            <option @if(old('hall_id', $student->hall_id)==$hall->id)
                                selected @endif
                                value="{{ $hall->id }}">{{ $hall->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label>Roll Number</label>
                        <input type="text" name="roll" placeholder="Roll Number..."
                            value="{{old('roll', $student->roll)}}">
                    </div>

                    <div class="field required">
                        <label>Registration Number</label>
                        <input type="text" name="registration" placeholder="Registration Number..."
                            value="{{old('registration', $student->registration)}}" required>
                    </div>

                    <br>
                    <button type="submit" class="ui blue submit button">Save</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection