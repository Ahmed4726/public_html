@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Hall List</div>
    </div>
@endsection
@section('title', 'Hall List')
@section('icon', "list")
@section('subtitle', 'Hall List')

@can('ADMIN')
    @section('createButton')
        <a href="{{route('Laralum::hall::create')}}" class='large ui green right floated button white-text'>Create Hall</a>
    @endsection
@endcan

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        @can('ADMIN')<div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by name..." class="" name="search" value="{{ Request::get('search') }}">

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            <a class="float-right" href="{{route('Laralum::hall::list')}}">Clear Search</a>
        </div>@endcan

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th class="one wide">#</th>@endif
                        <th class="five wide">Name</th>
                        <th class="six wide">Provost</th>
                        <th class="four wide">Options</th>
                    </tr>
                    </thead>
                    <tbody @if($sortable)id="sortable"@endif>
                    @foreach($halls as $key => $hall)
                        <tr data-item="{{$hall->id}}">
                            @if($sortable)<td>{{$key+ $halls->firstItem()}}</td>@endif
                            <td> {{ $hall->name }}</td>
                            <td>
                                @if($hall->teacher()->exists())
                                    <label class="ui basic label"> {{ $hall->teacher->name }} </label>
                                @endif
                                @can('ADMIN')
                                    @if(!$hall->teacher_id)
                                            <a href="{{ route('Laralum::hall::assign', ['hall' => $hall->id]) }}" class="ui purple tiny icon basic button">Assign</a>
                                    @endif
                                @endcan
                            </td>
                            <td>
                                <a href="{{ route('Laralum::hall::edit', ['hall' => $hall->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                @can('ADMIN')
                                    @if($hall->teacher()->exists())
                                        <a href="{{ route('Laralum::hall::unassign', ['hall' => $hall->id]) }}" class="mini ui orange icon button"><i class="remove icon"></i> Unassign</a>
                                    @else
                                        <a href="{{ route('Laralum::hall::assign', ['hall' => $hall->id]) }}" class="mini ui green icon button"><i class="check icon"></i> Assign</a>
                                    @endif
                                    <a href="/{{$hall->id}}/delete" class="delete mini ui icon red button">
                                        <i class="trash alternate outline icon"></i> Delete
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @can('ADMIN') @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/> @endif @endcan
                {{ $halls->links() }}

            </div>
            <br>
        </div>
    </div>

    {{-- Delete Confirmation Modal Start --}}
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
    {{-- Delete Confirmation Modal End --}}
@endsection

@section('js')

    {{-- Script For Delete Interaction Start --}}
    <script type="text/javascript">

        $(document).ready( function() {

            $('.delete').on("click", function (e) {
                e.preventDefault();
                var _this = $(this);

                $('.mini.modal').modal({
                    onApprove: function() {
                        window.location.href = "{{route('Laralum::hall::list')}}"+_this.attr('href');
                    }
                }).modal('show');
            });

            $('.ui.dropdown').dropdown();
            $('.pop').popup();
        });

    </script>
    {{-- Script For Delete Interaction End --}}

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

                $( "#sortable" ).sortable( {
                    update: function( event, ui ) {
                        $(this).children().each(function(index) {
                            $(this).find('td').first().html(index + {{$halls->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\Hall',
                            field : 'sorting_order',
                            orderStart : {{$halls->firstItem()}}
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
