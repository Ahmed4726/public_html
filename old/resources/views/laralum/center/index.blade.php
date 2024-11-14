@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Centers / Offices List</div>
    </div>
@endsection
@section('title', 'Centers / Offices List')
@section('icon', "list")
@section('subtitle', 'Centers / Offices List')

@can('ADMIN')
@section('createButton')
    <a href="{{route('Laralum::center::create')}}" class='large ui green right floated button white-text'>Create Center / Office</a>
@endsection
@endcan

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        @can('ADMIN')
            <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by name..." class="" name="search" value="{{ Request::get('search') }}">

                    <select class="ui selection dropdown"  name="type_id">
                        <option value="">Please Select a type</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" @if(Request::get('type_id') == $type->id) selected @endif>{{ $type->name }}</option>
                        @endforeach
                    </select>

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            <a class="float-right" href="{{route('Laralum::center::list')}}">Clear Search</a>
        </div>
        @endcan

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th class="six wide">Name</th>
                        <th class="one wide">Type</th>
                        <th class="six wide">Director</th>
                        <th class="three wide">Options</th>
                    </tr>
                    </thead>
                    <tbody @if($sortable)id="sortable"@endif>
                    @foreach($centers as $key => $center)
                        <tr data-item="{{$center->id}}">
                            @if($sortable)<td>{{$key+ $centers->firstItem()}}</td>@endif
                            <td> {{ $center->name }}</td>
                            <td> @if($center->typeInfo()->exists()) {{ $center->typeInfo->name }}  @endif </td>
                            <td>
                                @if(is_null($center->teacher_id) && $center->director_name)
                                    {{ $center->director_name }}
                                @elseif($center->teacher()->exists())
                                    <label class="ui basic label"> {{ $center->teacher->name }} </label>
                                @endif

                                @can('ADMIN')
                                    @if(is_null($center->teacher_id))
                                            <a href="{{ route('Laralum::center::assign', ['center' => $center->id]) }}" class="ui purple tiny basic icon button">Assign</a>
                                    @endif
                                @endcan
                            </td>
                            <td>
                                <a href="{{ route('Laralum::center::edit', ['center' => $center->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                @can('ADMIN')
                                    @if($center->teacher()->exists())
                                        <a href="{{ route('Laralum::center::unassign', ['center' => $center->id]) }}" class="mini ui red icon button"><i class="remove icon"></i> Unassign</a>
                                    @else
                                        <a href="{{ route('Laralum::center::assign', ['center' => $center->id]) }}" class="mini ui green icon button"><i class="check icon"></i> Assign</a>
                                    @endif
                                @endcan
                                <a href="{{ route('Laralum::center::advance', ['center' => $center->id]) }}" class="mini ui blue icon button"><i class="setting icon"></i> Advanced Option</a>
                                {{--<div class="ui blue top icon right pointing dropdown button">
                                    Edit
                                    <div class="menu">
                                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                                        <a href="{{ route('Laralum::center::edit', ['center' => $center->id]) }}" class="item">
                                            <i class="edit icon"></i>
                                            Basic Edit
                                        </a>
                                        @can('ADMIN')
                                            @if($center->teacher()->exists())
                                                <a href="{{ route('Laralum::center::unassign', ['center' => $center->id]) }}" class="item">
                                                    <i class="remove icon"></i>
                                                    Unassign </a>
                                            @else
                                                <a href="{{ route('Laralum::center::assign', ['center' => $center->id]) }}" class="item">
                                                    <i class="check icon"></i> Assign</a>
                                            @endif
                                        @endcan

                                        <div class="header">Advanced Options</div>
                                        <a href="{{route('Laralum::center::upload::list', ['center' => $center->id])}}" class="item">
                                            <i class="upload icon"></i>
                                            Uploads
                                        </a>
                                        <a href="{{route('Laralum::center::officer::list', ['center' => $center->id])}}" class="item">
                                            <i class="users icon"></i>
                                            Officers
                                        </a>
                                        <a href="{{route('Laralum::center::program::list', ['center' => $center->id])}}" class="item">
                                            <i class="print icon"></i>
                                            Programs
                                        </a>
                                        <a href="{{route('Laralum::center::facility::list', ['center' => $center->id])}}" class="item">
                                            <i class="list icon"></i>
                                            Facilities
                                        </a>
                                    </div>
                                </div>--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @can('ADMIN') @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/> @endif @endcan
                {{ $centers->links() }}

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
                            $(this).find('td').first().html(index + {{$centers->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\Center',
                            field : 'sorting_order',
                            orderStart : {{$centers->firstItem()}}
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
