@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(isset($uri)) <a class="section" href="{{ route("Laralum::$uri::list") }}">{{ucfirst($uri)}} List</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route("Laralum::$uri::advance", [$uri => $uriValue]) }}">Advanced Option</a>
        <i class="right angle icon divider"></i>@endif
        <div class="active section">Researches List</div>
    </div>
@endsection
@section('title', 'Researches List')
@section('icon', "list")
@section('subtitle', 'Researches List')

@section('createButton')
    @if(isset($uri)) <a href="{{route("Laralum::$uri::research::create", [$uri => $uriValue])}}" class='large ui green right floated button white-text'>Create Research</a>
    @else <a href="{{route('Laralum::research::create')}}" class='large ui green right floated button white-text'>Create Research</a> @endif
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by name..." class="" name="search" value="{{ Request::get('search') }}">

                    @if(!isset($uri))<select class="ui selection dropdown" name="department_id">
                        <option value="">Please Select department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @if(Request::get('department_id') == $department->id) selected @endif>{{ $department->name }}</option>
                        @endforeach
                    </select>@endif

                    <select class="ui selection dropdown" name="type_id">
                        <option value="">Please Select Type</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" @if(Request::get('type_id') == $type->id) selected @endif>{{ $type->name }}</option>
                        @endforeach
                    </select>

                    <select class="ui selection dropdown" name="status">
                        <option value="">Please Select status</option>
                        <option value="1" @if(Request::get('status') == 1) selected @endif>Enable</option>
                        <option value="disable" @if(Request::get('status') == 'disable') selected @endif>Disable</option>
                    </select>

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            @if(isset($uri)) <a class="float-right" href="{{route("Laralum::$uri::research::list", [$uri => $uriValue])}}">Clear Search</a>
            @else <a class="float-right" href="{{route('Laralum::research::list')}}">Clear Search</a> @endif
        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th>Name</th>
                        <th>Type</th>
                        <th>Department</th>
                        <th>Website</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody @if($sortable)id="sortable"@endif>
                    @foreach($researches as $key => $research)
                        <tr data-item="{{$research->id}}">
                            @if($sortable)<td>{{$key+ $researches->firstItem()}}</td>@endif
                            <td>
                                @if(!$research->enabled)
                                    <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                                @else
                                    <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                                @endif
                                {{ $research->name }}
                            </td>
                            <td> @if($research->typeInfo()->exists()) <label class="ui basic label">{{ $research->typeInfo->name }}</label>@endif </td>
                            <td> @if($research->department()->exists()) {{ $research->department->name }} @endif </td>
                            <td>{{\Illuminate\Support\Str::limit($research->website_link, 20)}}</td>
                            <td>
                                <a href="{{ route('Laralum::research::edit', ['research' => $research->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                <a href="{{ route('Laralum::research::delete', ['research' => $research->id]) }}" class="delete mini ui red icon button"><i class="delete icon"></i> Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/> @endif
                {{ $researches->links() }}

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
                            $(this).find('td').first().html(index + {{$researches->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\Research',
                            field : 'sorting_order',
                            orderStart : {{$researches->firstItem()}}
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
