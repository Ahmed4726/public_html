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
                        <select name="admission_session_id">
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

                    <div class="field">
                        <label>Department</label>
                        <input type="text" name="department" placeholder="Department Name..."
                            value="{{old('department', $student->department)}}">
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

                    <div class="field">
                        <label>Hall Name</label>
                        <input type="text" name="hall" placeholder="Hall Name..."
                            value="{{old('hall', $student->hall)}}">
                    </div>

                    <br>
                    <button type="submit" class="ui blue submit button">Save</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection