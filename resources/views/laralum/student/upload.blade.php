@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::student::list') }}">Student List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Student Bulk Upload</div>
</div>
@endsection
@section('title', 'Student Bulk Upload')
@section('icon', "plus")
@section('subtitle', 'Student Bulk Upload')
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST" action="{{ route('Laralum::student::upload') }}"
                enctype="multipart/form-data">

                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Admission Session</label>
                        <select name="admission_session_id" required>
                            <option value="">--- Admission Session ---</option>
                            @foreach($sessions as $session)
                            <option @if(old('admission_session_id')==$session->id)
                                selected @endif
                                value="{{ $session->id }}">{{ $session->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field required">
                        <label>Program</label>
                        <select name="program_id" required>
                            <option value="">--- Program ---</option>
                            @foreach($programs as $key => $program)
                            <option @if(old('program_id')==$key) selected @endif value="{{ $key }}">{{ $program }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field required">
                        <label>Upload</label>
                        <input type="file" class="form-control-file" name="file" id="exampleFormControlFile1" required>
                        <p class="mt-2 italic">
                            <a href="{{asset('SampleStudentUpload.xlsx')}}" target="_blank"> Sample upload file </a>
                        </p>
                    </div>

                    <br>
                    <button type="submit" class="ui blue submit button">Upload</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection