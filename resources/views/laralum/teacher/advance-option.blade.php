@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::teacher::list') }}">Teacher List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Advanced Option</div>
    </div>
@endsection
@section('title', 'Advanced Option')
@section('icon', "plus")
@section('subtitle', $teacher->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column">
                <div class="ui padded segment" style="text-align: center; min-height: 300px">
                    <div style="margin-top: 10%">
                        <a href="{{ route('Laralum::teacher::publication::list', ['teacher' => $teacher->id]) }}" class="ui big green button">
                            <i class="print icon"></i><br/>
                            Publication
                        </a>
                        <a href="{{ route('Laralum::teacher::teaching::list', ['teacher' => $teacher->id]) }}" class="ui big blue button">
                            <i class="print icon"></i><br/>
                            Teaching
                        </a>
                        <a href="{{ route('Laralum::teacher::education::list', ['teacher' => $teacher->id]) }}" class="ui big orange button">
                            <i class="book icon"></i><br/>
                            Academic
                        </a>
                        <a href="{{ route('Laralum::teacher::experience::list', ['teacher' => $teacher->id]) }}" class="ui big olive button">
                            <i class="wrench icon"></i><br/>
                            Experience
                        </a>
                        <a href="{{ route('Laralum::teacher::activity::list', ['teacher' => $teacher->id]) }}" class="ui big yellow button">
                            <i class="list icon"></i><br/>
                            Activity
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

