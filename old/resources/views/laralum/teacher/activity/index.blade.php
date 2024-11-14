@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::teacher::list') }}">Teacher List</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route('Laralum::teacher::advance', ['teacher' => $teacher->id]) }}">Advanced Option</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Activity List</div>
    </div>
@endsection
@section('title', 'Activity List')
@section('icon', "list")
@section('subtitle', $teacher->name)

@section('createButton')
    <a href="{{route('Laralum::teacher::activity::create', ['teacher' => $teacher->id])}}" class='large ui green right floated button white-text'>Add Teacher Activity</a>
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by organization, position, period..."  name="search" value="{{ Request::get('search') }}">

                    {{--<select class="ui dropdown"  name="status">
                        <option value="">Please Select a Status</option>
                        <option value="1" @if(Request::get('status') == 1) selected @endif>Active</option>
                        <option value="inactive" @if(Request::get('status') == "inactive") selected @endif>In-Active</option>
                    </select>--}}

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            <a class="float-right" href="{{route('Laralum::teacher::activity::list', ['teacher' => $teacher->id])}}">Clear Search</a>
        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th class="four wide">Organization</th>
                        <th class="one wide">Position</th>
                        <th class="seven wide">Description</th>
                        <th class="one wide">Period</th>
                        <th class="three wide">Options</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @foreach($activities as $key => $activity)
                        <tr data-item="{{$activity->id}}">
                            @if($sortable)<td>{{$key+ $activities->firstItem()}}</td>@endif
                            <td>
                                {{--@if(!$activity->status)
                                    <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                                @else
                                    <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                                @endif--}}
                                {{ $activity->organization }}
                            </td>
                            <td>{{ $activity->position }}</td>
                            <td>{!! $activity->description !!}</td>
                                <td>{{ $activity->period }}</td>
                            <td>
                                <a href="{{ route('Laralum::teacher::activity::edit', ['teacher' => $teacher->id, 'activity' => $activity->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                <a href="{{ route('Laralum::teacher::activity::delete', ['teacher' => $teacher->id, 'activity' => $activity->id]) }}" class="delete mini ui red icon button"><i class="delete icon"></i> Delete</a>
                        </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/> @endif
                {{ $activities->links() }}

            </div>
            <br>
        </div>
    </div>

    <div class="ui mini modal">
        <div class="ui icon header">
            <i class="archive icon"></i>
            Are you sure you want to delete this item?
        </div>

        <div class="actions">
            <div class="ui negative button">
                No
            </div>
            <div class="ui positive right labeled icon button">
                Yes
                <i class="checkmark icon"></i>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .mini.button {
            margin: 1px !important;
        }
    </style>
@endsection

@section('js')

    <script type="text/javascript">

        $(document).ready( function() {

            $('.delete').on("click", function (e) {
                e.preventDefault();
                var _this = $(this);

                $('.mini.modal').modal({
                    onApprove: function() {
                        window.location.href = _this.attr('href');
                    }
                }).modal('show');
            });
        });

    </script>

    @if($sortable)
        @include('laralum.include.jquery-ui')
        @include('laralum.include.pnotify')
        @include('laralum.include.vue.vue-axios')

        <script type="text/javascript">

            $(document).ready( function() {

                $('td, th', '#sortable').each(function () {
                    var cell = $(this);
                    cell.width(cell.width());
                });

                // $( "#sortable" ).sortable().disableSelection();

                $( "#sortable" ).sortable( {
                    update: function( event, ui ) {
                        $(this).children().each(function(index) {
                            $(this).find('td').first().html(index + {{$activities->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\TeacherActivity',
                            field : 'sorting_order',
                            orderStart : {{$activities->firstItem()}}
                        }
                    }).
                    then(response => {
                        if(response.status == 200) {
                            PNotify.success({title: 'Success', text: response.data})
                        }
                        else PNotify.error('Something Went Wrong!');
                    });
                });
            });

        </script>
    @endif

@endsection
