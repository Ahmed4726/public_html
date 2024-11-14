@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::department::list') }}">Department List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Advanced Option</div>
    </div>
@endsection
@section('title', 'Advanced Option')
@section('icon', "plus")
@section('subtitle', $department->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column">
                <div class="ui very padded segment">

                    <div class="row center fluid">
                        <a href="{{route('Laralum::department::teacher::list', ['department' => $department->id])}}" class="ui huge orange button ten">
                            <i class="user icon"></i><br/>
                            Teachers
                        </a>
                        <a href="{{route('Laralum::department::officer::list', ['department' => $department->id])}}" class="ui huge olive button">
                            <i class="users icon"></i><br/>
                            Employee
                        </a>
                        <a href="{{route('Laralum::department::event::list', ['department' => $department->id])}}" class="ui huge yellow button">
                            <i class="calendar icon"></i><br/>
                            All Notice
                        </a>
                    </div>

                    <div class="row center">
                        <a href="{{route('Laralum::department::program::list', ['department' => $department->id])}}" class="ui huge green button">
                            <i class="print icon"></i><br/>
                            Programs
                        </a>
                        <a href="{{route('Laralum::department::facility::list', ['department' => $department->id])}}" class="ui huge teal button">
                            <i class="list icon"></i><br/>
                            Facilities
                        </a>
                        <a href="{{route('Laralum::department::research::list', ['department' => $department->id])}}" class="ui huge blue button">
                            <i class="wrench icon"></i><br/>
                            Researches
                        </a>
                    </div>

                    <div class="row center">

                        <a href="{{route('Laralum::department::link::list', ['department' => $department->id])}}" class="ui huge violet button" style="width: 21%">
                            <i class="chain icon"></i><br/>
                            Links
                        </a>
                        <a href="{{ route('Laralum::department::upload', ['department' => $department->id]) }}" class="ui huge red button" style="width: 21%">
                            <i class="upload icon"></i><br/>
                            Upload
                        </a>
                        <a href="{{route('Laralum::department::gallery::image::list', ['department' => $department->id])}}" class="ui huge purple button" style="width: 21%">
                            <i class="image icon"></i><br/>
                            Gallery
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .segment a {
            margin: 5px !important;
        }

        .center {
            text-align: center;
            margin-bottom: 10px;
        }

        .segment .icon {
            margin-bottom: 5px !important;
        }
    </style>
@endsection

