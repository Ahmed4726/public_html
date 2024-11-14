@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">Department List</div>
</div>
@endsection
@section('title', 'Department List')
@section('icon', "list")
@section('subtitle', 'Department List')

@can('ADMIN')
@section('createButton')
<a href="{{route('Laralum::department::create')}}" class='large ui green right floated button white-text'>Create
    Department</a>
@endsection
@endcan

@section('content')

<div class="ui one column doubling stackable grid container" id="vue-app">

    @can('ADMIN')
    <div class="column">
        <form>
            <div class="ui fluid action input">
                <input type="text" placeholder="Search by name, short name..." class="" v-model="search" name="search"
                    value="{{ Request::get('search') }}">

                <select class="ui selection dropdown" v-model="category_id" name="faculty_id">
                    <option value="">Please Select a faculty</option>
                    @foreach($faculties as $faculty)
                    <option value="{{ $faculty->id }}" @if(Request::get('faculty_id')==$faculty->id) selected
                        @endif>{{ $faculty->name }}</option>
                    @endforeach
                </select>

                <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
            </div>
        </form>
        <a class="float-right" href="{{route('Laralum::department::list')}}">Clear Search</a>
    </div>
    @endcan

    <div class="column">
        <div class="ui very padded segment">

            <table class="ui selectable striped celled small table">
                <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th class="six wide">Name</th>
                        <th class="one wide">Short Name</th>
                        <th class="two wide">Faculty</th>
                        <th class="four wide">Chairman</th>
                        <th class="three wide">Options</th>
                    </tr>
                </thead>
                <tbody @if($sortable)id="sortable" @endif>
                    @foreach($departments as $key => $department)
                    <tr data-item="{{$department->id}}">
                        @if($sortable)<td>{{$key+ $departments->firstItem()}}</td>@endif
                        <td> {{ $department->name }}</td>
                        <td>{{ $department->short_name }}</td>
                        <td>{{ $department->faculty->name }}</td>
                        <td>
                            @if($department->chairman()->exists())
                            <label class="ui basic label"> {{ $department->chairman->name }} </label>
                            @endif

                            @can('ADMIN')
                            @if(is_null($department->teacher_id))
                            <a href="{{ route('Laralum::department::assign', ['department' => $department->id]) }}"
                                class="ui purple tiny basic icon button">Assign</a>
                            @endif
                            @endcan
                        </td>
                        <td>
                            <a href="{{ route('Laralum::department::edit', ['department' => $department->id]) }}"
                                class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                            @can('ADMIN')
                            @if($department->chairman()->exists())
                            <a href="{{ route('Laralum::department::unassign', ['department' => $department->id]) }}"
                                class="mini ui red icon button"><i class="remove icon"></i> Unassign </a>
                            @else
                            <a href="{{ route('Laralum::department::assign', ['department' => $department->id]) }}"
                                class="mini ui green icon button"><i class="check icon"></i> Assign</a>
                            @endif
                            @endcan
                            <a href="{{ route('Laralum::department::advance', ['department' => $department->id]) }}"
                                class="mini ui blue icon button"><i class="setting icon"></i> Advanced Option</a>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i
                    class="icon list"></i>Update Order</button><br /><br /> @endif
            {{ $departments->links() }}

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
                            $(this).find('td').first().html(index + {{$departments->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\Department',
                            field : 'sorting_order',
                            orderStart : {{$departments->firstItem()}}
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