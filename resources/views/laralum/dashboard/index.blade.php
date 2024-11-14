@extends('layouts.admin.panel')

@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">{{ trans('laralum.dashboard') }}</div>
</div>
@endsection

@section('title', trans('laralum.dashboard'))

@section('icon', "dashboard")

@section('subtitle')
{{ trans('laralum.welcome_user', ['name' => Laralum::loggedInUser()->name]) }}
@endsection

@section('content')
<div class="ui doubling stackable two column grid container">

    <div class="column">
        <div class="ui padded segment">
            <div class='ui doubling stackable three column grid container'>
                <div class='column'>
                    <center>
                        <div class='ui statistic'>
                            <div class='value'>{{\App\Faculty::whereType('FACULTY')->count()}}</div>
                            <div class='label'>Faculty</div>
                        </div>
                    </center>
                </div>
                <div class='column'>
                    <center>
                        <div class='ui statistic'>
                            <div class='value'>{{\App\Department::whereFacultyId(43)->count()}}</div>
                            <div class='label'>Institute</div>
                        </div>
                    </center>
                </div>
                <div class='column'>
                    <center>
                        <div class='ui statistic'>
                            <div class='value'>{{\App\Department::where('faculty_id', '!=', 43)->count()}}</div>
                            <div class='label'>Department</div>
                        </div>
                    </center>
                </div>
            </div>
        </div>
        <br>
        <div class="ui padded segment">
            <div class='ui doubling stackable three column grid container'>
                <div class='column'>
                    <center>
                        <div class='ui statistic'>
                            <div class='value'>{{\App\Hall::count()}}</div>
                            <div class='label'>Hall</div>
                        </div>
                    </center>
                </div>
                <div class='column'>
                    <center>
                        <div class='ui statistic'>
                            <div class='value'>{{\App\Center::whereTypeId(1)->count()}}</div>
                            <div class='label'>Center</div>
                        </div>
                    </center>
                </div>
                <div class='column'>
                    <center>
                        <div class='ui statistic'>
                            <div class='value'>{{\App\Center::whereTypeId(2)->count()}}</div>
                            <div class='label'>Office</div>
                        </div>
                    </center>
                </div>
            </div>
        </div>
        <br>
        <div class="ui padded segment">
            <div class='ui doubling stackable three column grid container'>
                <div class='column'>
                    <center>
                        <div class='ui statistic'>
                            <div class='value'>{{\App\Teacher::whereIn('status', [1, 2, 4])->count()}}</div>
                            <div class='label'>Teacher</div>
                        </div>
                    </center>
                </div>
                <div class='column'>
                    <center>
                        <div class='ui statistic'>
                            <div class='value'>{{\App\Officer::whereStatus(1)->whereTypeId(1)->count()}}</div>
                            <div class='label'>Officer</div>
                        </div>
                    </center>
                </div>
                <div class='column'>
                    <center>
                        <div class='ui statistic'>
                            <div class='value'>{{\App\Officer::whereStatus(1)->whereTypeId(2)->count()}}</div>
                            <div class='label'>Staff</div>
                        </div>
                    </center>
                </div>
            </div>
        </div>
        <br>
    </div>

    <div class="column">
        <div class="ui padded segment" style="padding-bottom: 25px;">
            {!! Laralum::barChart('Teacher Per Designation', 'Teacher', $designations, $totalTeacher) !!}
        </div>
        <br>
    </div>

</div>
@endsection

@section('js')
{!! Laralum::includeAssets('charts') !!}
@endsection