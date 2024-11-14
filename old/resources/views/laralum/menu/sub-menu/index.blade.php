@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::menu::list') }}">Menu List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Sub Menu List</div>
    </div>
@endsection
@section('title', 'Sub Menu List')
@section('icon', "list")
@section('subtitle', $menu->display_text)

@section('createButton')
    <a href="{{route('Laralum::menu::submenu::create', ['menu' => $menu->id])}}" class='large ui green right floated button white-text'>Create Sub Menu</a>
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
            <a class="float-right" href="{{route('Laralum::menu::submenu::list', ['menu' => $menu->id])}}">Clear Search</a>
        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th class="five wide">Display Name</th>
                        <th class="five wide">URL</th>
                        <th class="two wide">Type</th>
                        <th class="three wide">Options</th>
                    </tr>
                    </thead>
                    <tbody @if($sortable)id="sortable"@endif>
                    @foreach($subMenus as $key => $subMenu)
                        <tr data-item="{{$subMenu->id}}">
                            @if($sortable)<td>{{$key+ $subMenus->firstItem()}}</td>@endif
                            <td>
                                @if(!$subMenu->enabled)
                                    <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                                @else
                                    <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                                @endif
                                {{ $subMenu->display_text }}
                            </td>
                            <td>{{$subMenu->link}}</td>
                            <td><label class="ui basic label">{{$subMenu->type}}</label></td>
                            <td>
                                <a href="{{ route('Laralum::menu::submenu::edit', ['menu' => $menu->id, 'submenu' => $subMenu->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                <a href="{{ route('Laralum::menu::submenu::delete', ['menu' => $menu->id, 'submenu' => $subMenu->id]) }}" class="delete mini ui red icon button"><i class="delete icon"></i> Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/> @endif
                {{ $subMenus->links() }}

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
                            $(this).find('td').first().html(index + {{$subMenus->firstItem()}})
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
                            orderStart : {{$subMenus->firstItem()}}
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
