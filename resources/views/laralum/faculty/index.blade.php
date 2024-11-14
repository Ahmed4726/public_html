@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Faculty List</div>
    </div>
@endsection
@section('title', 'Faculty List')
@section('icon', "list")
@section('subtitle', 'Faculty List')

@can('ADMIN')
    @section('createButton')
        <a href="{{route('Laralum::faculty::create')}}" class='large ui green right floated button white-text'>Create Faculty</a>
    @endsection
@endcan

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        @can('ADMIN')<div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by name..." class="" v-model="search" name="search" value="{{ Request::get('search') }}">

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            <a class="float-right" href="{{route('Laralum::faculty::list')}}">Clear Search</a>
        </div>@endcan

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th class="six wide">Name</th>
                        <th class="one wide">Type</th>
                        <th class="five wide">Dean</th>
                        <th class="four wide">Options</th>
                    </tr>
                    </thead>
                    <tbody @if($sortable)id="sortable"@endif>
                    @foreach($faculties as $key => $faculty)
                        <tr data-item="{{$faculty->id}}">
                            @if($sortable)<td>{{$key+ $faculties->firstItem()}}</td>@endif
                            <td> {{ $faculty->name }}</td>
                            <td>{{ $faculty->type }}</td>
                            <td> @if($faculty->dean()->exists())
                                    <label class="ui basic label"> {{ $faculty->dean->name }} </label>
                                @else
                                    @can('ADMIN') <a href="{{ route('Laralum::faculty::assign', ['faculty' => $faculty->id]) }}" class="ui purple tiny basic icon button">Assign</a> @endcan
                                @endif</td>
                            <td>
                                <a href="{{ route('Laralum::faculty::edit', ['faculty' => $faculty->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                
                                <a href="{{ route('Laralum::faculty::facultyEventlist', ['faculty' => $faculty->id]) }}" class="mini ui green icon button"><i class="edit icon"></i> Event List</a>

                                @can('ADMIN')
                                    @if($faculty->dean()->exists())
                                        <a href="{{ route('Laralum::faculty::unassign', ['faculty' => $faculty->id]) }}" class="mini ui red icon button"><i class="remove icon"></i> Unassign</a>
                                    @else
                                        <a href="{{ route('Laralum::faculty::assign', ['faculty' => $faculty->id]) }}" class="mini ui green icon button"><i class="check icon"></i> Assign</a>
                                    @endif
                                    <a href='{{ url("admin/department?faculty_id=$faculty->id") }}' class="mini ui blue teal button"><i class="list icon"></i> ALL Department</a>
                                @endcan

                                {{--<div class="ui blue top icon left pointing dropdown button">
                                    Edit
                                    <div class="menu">
                                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                                        <a href="{{ route('Laralum::faculty::edit', ['faculty' => $faculty->id]) }}" class="item">
                                            <i class="edit icon"></i>
                                            Basic Edit
                                        </a>
                                        @can('ADMIN')
                                            @if($faculty->dean()->exists())
                                                <a href="{{ route('Laralum::faculty::unassign', ['faculty' => $faculty->id]) }}" class="item">
                                                    <i class="remove icon"></i>
                                                    Unassign </a>
                                            @else
                                                <a href="{{ route('Laralum::faculty::assign', ['faculty' => $faculty->id]) }}" class="item">
                                                    <i class="check icon"></i> Assign</a>
                                            @endif

                                            <div class="header">Advanced Options</div>
                                            <a href='{{ url("admin/department?faculty_id=$faculty->id") }}' class="item">
                                                <i class="list icon"></i>
                                                ALL Department
                                            </a>
                                        @endcan
                                    </div>
                                </div>--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @can('ADMIN') @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/> @endif @endcan
                {{ $faculties->links() }}

            </div>
            <br>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .mini {
            margin: 1px !important;
        }
    </style>
@endsection

@section('js')

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
                            $(this).find('td').first().html(index + {{$faculties->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\Faculty',
                            field : 'sorting_order',
                            orderStart : {{$faculties->firstItem()}}
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
