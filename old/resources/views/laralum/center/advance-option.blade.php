@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::center::list') }}">Center List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Advanced Option</div>
    </div>
@endsection
@section('title', 'Advanced Option')
@section('icon', "plus")
@section('subtitle', $center->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column">
                <div class="ui very padded segment" style="text-align: center; min-height: 300px">
                    <div style="margin-top: 10%">
                        <a href="{{route('Laralum::center::upload::list', ['center' => $center->id])}}" class="ui huge red button">
                            <i class="upload icon"></i><br/>
                            Uploads
                        </a>
                        <a href="{{route('Laralum::center::officer::list', ['center' => $center->id])}}" class="ui huge green button">
                            <i class="users icon"></i><br/>
                            Employee
                        </a>
                        <a href="{{route('Laralum::center::program::list', ['center' => $center->id])}}" class="ui huge yellow button">
                            <i class="print icon"></i><br/>
                            Programs
                        </a>
                        <a href="{{route('Laralum::center::facility::list', ['center' => $center->id])}}" class="ui huge teal button">
                            <i class="list icon"></i><br/>
                            Facilities
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

        .segment .icon {
            margin-bottom: 5px !important;
        }
    </style>
@endsection

