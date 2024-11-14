@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(isset($uri)) <a class="section" href="{{ route("Laralum::$uri::list") }}">{{ucfirst($uri)}} List</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route("Laralum::$uri::advance", [$uri => $uriValue]) }}">Advanced Option</a>
        <i class="right angle icon divider"></i>@endif
        <div class="active section">Link List</div>
    </div>
@endsection
@section('title', 'Link List')
@section('icon', "list")
@section('subtitle', 'Link List')

@section('createButton')
    @if(isset($uri)) <a href="{{route("Laralum::$uri::link::create", [$uri => $uriValue])}}" class='large ui green right floated button white-text'>Create Link</a>
    @else <a href="{{route('Laralum::link::create')}}" class='large ui green right floated button white-text'>Create Link</a> @endif
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        <div class="column">

            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by name..." class="" name="search" value="{{ Request::get('search') }}">

                    <select class="ui selection dropdown" name="type_id">
                        <option value="">Please Select type</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" @if(Request::get('type_id') == $type->id) selected @endif>{{ $type->name }}</option>
                        @endforeach
                    </select>

                    <select class="ui compact selection dropdown" name="status">
                        <option value="">Please Select status</option>
                        <option value="1" @if(Request::get('status') == 1) selected @endif>Enabled</option>
                        <option value="disable" @if(Request::get('status') == 'disable') selected @endif>Disabled</option>
                    </select>

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>

            @if(isset($uri)) <a class="float-right" href="{{route("Laralum::$uri::link::list", [$uri => $uriValue])}}">Clear Search</a>
            @else <a class="float-right" href="{{route('Laralum::link::list')}}">Clear Search</a> @endif

        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th>Label</th>
                        <th>Type</th>
                        <th>Department</th>
                        <th>URL</th>
                        <th>Target</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody @if($sortable)id="sortable"@endif>
                    @foreach($links as $key => $link)
                        <tr data-item="{{$link->id}}">
                            @if($sortable)<td>{{$key+ $links->firstItem()}}</td>@endif
                            <td>
                                @if(!$link->enabled)
                                    <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                                @else
                                    <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                                @endif
                                {{ $link->label }}
                            </td>
                            <td> @if($link->typeInfo()->exists()) {{ $link->typeInfo->name }} @endif </td>
                            <td>
                                @if($link->department_id && $link->department()->exists())
                                    {{ $link->department->short_name }}
                                @else
                                    <label class="ui basic label">Global</label>
                                @endif
                            </td>
                            <td>{{$link->link_url}}</td>
                            <td>{{$link->target}}</td>
                            <td>
                                <a href="{{ route('Laralum::link::edit', ['link' => $link->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                <a href="{{ route('Laralum::link::delete', ['link' => $link->id]) }}" class="delete mini ui red icon button"><i class="delete icon"></i> Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/> @endif
                {{ $links->links() }}

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

                $( "#sortable" ).sortable( {
                    update: function( event, ui ) {
                        $(this).children().each(function(index) {
                            $(this).find('td').first().html(index + {{$links->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\Link',
                            field : 'sorting_order',
                            orderStart : {{$links->firstItem()}}
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
