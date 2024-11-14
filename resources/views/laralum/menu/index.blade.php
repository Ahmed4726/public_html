@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Menu List</div>
    </div>
@endsection
@section('title', 'Menu List')
@section('icon', "list")
@section('subtitle', 'Menu List')

@section('createButton')
    <a href="{{route('Laralum::menu::create')}}" class='large ui green right floated button white-text'>Create Menu</a>
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by display text..." class="" name="search" value="{{ Request::get('search') }}">

                    <select class="ui selection dropdown" name="status">
                        <option value="">Please Select status</option>
                        <option value="1" @if(Request::get('status') == 1) selected @endif>Enable</option>
                        <option value="disable" @if(Request::get('status') == 'disable') selected @endif>Disable</option>
                    </select>

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            <a class="float-right" href="{{route('Laralum::menu::list')}}">Clear Search</a>
        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th class="five wide">Display Name</th>
                        <th class="five wide">URL</th>
                        <th class="one wide">Type</th>
                        <th class="five wide">Options</th>
                    </tr>
                    </thead>
                    <tbody @if($sortable)id="sortable"@endif>
                    @foreach($menus as $key => $menu)
                        <tr data-item="{{$menu->id}}">
                            @if($sortable)<td>{{$key+ $menus->firstItem()}}</td>@endif
                            <td>
                                @if(!$menu->enabled)
                                    <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                                @else
                                    <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                                @endif
                                {{ $menu->display_text }}
                            </td>
                            <td>{{$menu->link}}</td>
                            <td><label class="ui basic label">{{$menu->type}}</label></td>
                            <td>
                                <a href="{{ route('Laralum::menu::edit', ['menu' => $menu->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                <a href="{{ route('Laralum::menu::submenu::list', ['menu' => $menu->id]) }}" class="mini ui green icon button"><i class="chain icon"></i> Sub-menu</a>
                                <a href="{{ route('Laralum::menu::delete', ['menu' => $menu->id]) }}" class="delete mini ui red icon button"><i class="delete icon"></i> Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/> @endif
                {{ $menus->links() }}

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
                            $(this).find('td').first().html(index + {{$menus->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\Menu',
                            field : 'sorting_order',
                            orderStart : {{$menus->firstItem()}}
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
